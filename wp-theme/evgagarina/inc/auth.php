<?php
/**
 * AJAX-вход (wp_signon) и восстановление пароля (retrieve_password) для окна входа.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

/**
 * Простое ограничение частоты запросов по IP.
 */
function evg_rate_limited( $action, $limit, $window ) {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'evg_rl_' . $action . '_' . md5( $ip );

	$count = (int) get_transient( $key );
	if ( $count >= $limit ) {
		return true;
	}
	set_transient( $key, $count + 1, $window );
	return false;
}

function evg_auth_verify_nonce() {
	if ( ! check_ajax_referer( 'evg_auth', 'nonce', false ) ) {
		wp_send_json_error(
			array(
				'code'    => 'nonce',
				'message' => __( 'Сессия устарела. Попробуйте ещё раз.', 'evgagarina' ),
				'nonce'   => wp_create_nonce( 'evg_auth' ),
			),
			403
		);
	}
}

function evg_login_redirect_url() {
	$fallback = evg_account_url();
	if ( empty( $_POST['redirect_to'] ) ) {
		return $fallback;
	}
	return wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ), $fallback );
}

/**
 * Свежий nonce — для страниц, отданных из кэша.
 */
function evg_ajax_auth_nonce() {
	nocache_headers();
	wp_send_json_success( array( 'nonce' => wp_create_nonce( 'evg_auth' ) ) );
}
add_action( 'wp_ajax_nopriv_evg_auth_nonce', 'evg_ajax_auth_nonce' );

function evg_ajax_login() {
	nocache_headers();

	if ( is_user_logged_in() ) {
		wp_send_json_success( array( 'redirect' => evg_login_redirect_url() ) );
	}

	evg_auth_verify_nonce();

	if ( evg_rate_limited( 'login', (int) apply_filters( 'evg_login_attempts', 10 ), 15 * MINUTE_IN_SECONDS ) ) {
		wp_send_json_error(
			array(
				'code'    => 'rate_limit',
				'message' => __( 'Слишком много попыток входа. Подождите 15 минут или восстановите пароль.', 'evgagarina' ),
			),
			429
		);
	}

	$login    = isset( $_POST['log'] ) ? sanitize_user( wp_unslash( $_POST['log'] ) ) : '';
	$password = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- пароль не модифицируется.

	if ( '' === $login ) {
		wp_send_json_error( array( 'code' => 'empty_username', 'field' => 'log', 'message' => __( 'Введите email или логин', 'evgagarina' ) ), 400 );
	}
	if ( '' === $password ) {
		wp_send_json_error( array( 'code' => 'empty_password', 'field' => 'pwd', 'message' => __( 'Введите пароль', 'evgagarina' ) ), 400 );
	}

	$user = wp_signon(
		array(
			'user_login'    => $login,
			'user_password' => $password,
			'remember'      => ! empty( $_POST['rememberme'] ),
		),
		is_ssl()
	);

	if ( is_wp_error( $user ) ) {
		$generic = array( 'invalid_username', 'invalid_email', 'incorrect_password', 'empty_username', 'empty_password' );
		$code    = $user->get_error_code();
		$message = in_array( $code, $generic, true )
			? __( 'Неверный email, логин или пароль', 'evgagarina' )
			: wp_strip_all_tags( $user->get_error_message() );

		wp_send_json_error( array( 'code' => $code, 'field' => 'pwd', 'message' => $message ), 401 );
	}

	wp_set_current_user( $user->ID );

	wp_send_json_success( array( 'redirect' => evg_login_redirect_url() ) );
}
add_action( 'wp_ajax_nopriv_evg_login', 'evg_ajax_login' );
add_action( 'wp_ajax_evg_login', 'evg_ajax_login' );

function evg_ajax_lost_password() {
	nocache_headers();
	evg_auth_verify_nonce();

	if ( evg_rate_limited( 'lost', (int) apply_filters( 'evg_lost_password_attempts', 5 ), HOUR_IN_SECONDS ) ) {
		wp_send_json_error(
			array(
				'code'    => 'rate_limit',
				'message' => __( 'Слишком много запросов. Попробуйте позже.', 'evgagarina' ),
			),
			429
		);
	}

	$login = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';
	if ( '' === $login ) {
		wp_send_json_error( array( 'code' => 'empty_username', 'message' => __( 'Введите email или логин', 'evgagarina' ) ), 400 );
	}

	$result = retrieve_password( $login );

	// Ответ одинаковый для существующих и несуществующих аккаунтов — чтобы нельзя было перебирать email.
	if ( is_wp_error( $result ) && 'retrieve_password_email_failure' === $result->get_error_code() ) {
		wp_send_json_error(
			array(
				'code'    => 'mail',
				'message' => __( 'Не удалось отправить письмо. Напишите нам — поможем восстановить доступ.', 'evgagarina' ),
			),
			500
		);
	}

	wp_send_json_success(
		array(
			'message' => sprintf(
				/* translators: %s: введённый логин или email */
				__( 'Если аккаунт «%s» существует, мы отправили на его email ссылку для сброса пароля. Проверьте почту и папку «Спам».', 'evgagarina' ),
				$login
			),
		)
	);
}
add_action( 'wp_ajax_nopriv_evg_lost_password', 'evg_ajax_lost_password' );

/**
 * Ссылка из письма сброса пароля ведёт на форму WooCommerce в личном кабинете,
 * а не на wp-login.php (если WooCommerce активен).
 */
function evg_reset_password_email( $defaults, $key, $user_login, $user_data ) {
	if ( ! function_exists( 'wc_lostpassword_url' ) ) {
		return $defaults;
	}

	$core_url = network_site_url( 'wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ), 'login' );
	$wc_url   = add_query_arg(
		array(
			'key' => $key,
			'id'  => $user_data->ID,
		),
		wc_lostpassword_url()
	);

	$defaults['message'] = str_replace( $core_url, $wc_url, $defaults['message'] );
	return $defaults;
}
add_filter( 'retrieve_password_notification_email', 'evg_reset_password_email', 10, 4 );

/**
 * Ссылка / разметка кнопки «Войти через Яндекс» от плагина.
 */
function evg_yandex_login_url() {
	$url = evg_mod( 'evg_yandex_login_url' );
	if ( $url ) {
		$url = add_query_arg( 'redirect_to', rawurlencode( evg_account_url() ), $url );
	}
	return apply_filters( 'evg_yandex_login_url', $url );
}

function evg_yandex_login_button() {
	$shortcode = trim( (string) evg_mod( 'evg_yandex_shortcode' ) );
	if ( $shortcode ) {
		echo '<div class="auth-modal__social">' . do_shortcode( $shortcode ) . '</div>';
		return true;
	}

	$url = evg_yandex_login_url();
	if ( ! $url ) {
		return false;
	}

	printf(
		'<a href="%s" class="auth-modal__yandex"><span class="auth-modal__yandex-mark" aria-hidden="true">Я</span>%s</a>',
		esc_url( $url ),
		esc_html__( 'Войти через Яндекс', 'evgagarina' )
	);
	return true;
}
