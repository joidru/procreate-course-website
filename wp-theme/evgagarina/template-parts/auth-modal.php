<?php
/**
 * Окно входа и восстановления пароля (для неавторизованных).
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$close_button = '<button type="button" class="auth-modal__close" aria-label="' . esc_attr__( 'Закрыть', 'evgagarina' ) . '" data-auth-close>' . evg_icon( 'close', 20 ) . '</button>';
?>
<dialog class="auth-modal" id="auth-modal" aria-labelledby="auth-title">
	<form class="auth-modal__form" id="auth-form" method="post" action="<?php echo esc_url( wp_login_url( evg_account_url() ) ); ?>" novalidate>
		<?php echo $close_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="auth-modal__head">
			<h2 id="auth-title" class="auth-modal__title"><?php esc_html_e( 'Вход в кабинет', 'evgagarina' ); ?></h2>
			<p class="auth-modal__lead"><?php esc_html_e( 'Ваши курсы, заказы и сертификаты', 'evgagarina' ); ?></p>
		</div>
		<div class="auth-field">
			<label for="auth-login"><?php esc_html_e( 'Email или логин', 'evgagarina' ); ?></label>
			<input id="auth-login" name="log" type="text" autocomplete="username" autocapitalize="none" spellcheck="false" required aria-describedby="auth-error">
		</div>
		<div class="auth-field">
			<div class="auth-field__row">
				<label for="auth-password"><?php esc_html_e( 'Пароль', 'evgagarina' ); ?></label>
				<button type="button" class="auth-modal__link" data-auth-view="lost"><?php esc_html_e( 'Забыли пароль?', 'evgagarina' ); ?></button>
			</div>
			<div class="auth-field__password">
				<input id="auth-password" name="pwd" type="password" autocomplete="current-password" required aria-describedby="auth-error">
				<button type="button" class="auth-field__toggle" aria-label="<?php esc_attr_e( 'Показать пароль', 'evgagarina' ); ?>" aria-pressed="false" data-auth-toggle>
					<span data-icon="show"><?php echo evg_icon( 'eye', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span data-icon="hide" hidden><?php echo evg_icon( 'eye-off', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</button>
			</div>
		</div>
		<label class="auth-modal__remember"><input type="checkbox" name="rememberme" value="forever" checked> <?php esc_html_e( 'Запомнить меня', 'evgagarina' ); ?></label>
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( evg_account_url() ); ?>">
		<p class="auth-modal__error" id="auth-error" role="alert" hidden></p>
		<button type="submit" class="auth-modal__submit"><?php esc_html_e( 'Войти', 'evgagarina' ); ?></button>
		<?php
		ob_start();
		$has_social = evg_yandex_login_button();
		do_action( 'evg_auth_modal_social' );
		$social = ob_get_clean();

		if ( $has_social || '' !== trim( $social ) ) :
			?>
			<div class="auth-modal__divider"><span><?php esc_html_e( 'или', 'evgagarina' ); ?></span></div>
			<?php
			echo $social; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- разметка плагина / уже экранирована.
		endif;
		?>
	</form>

	<form class="auth-modal__form" id="auth-lost-form" method="post" action="<?php echo esc_url( wp_lostpassword_url() ); ?>" novalidate hidden>
		<?php echo $close_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="auth-modal__head">
			<h2 id="auth-lost-title" class="auth-modal__title"><?php esc_html_e( 'Восстановление пароля', 'evgagarina' ); ?></h2>
			<p class="auth-modal__lead"><?php esc_html_e( 'Укажите email или логин — пришлём ссылку для создания нового пароля', 'evgagarina' ); ?></p>
		</div>
		<div data-auth-lost-fields>
			<div class="auth-field">
				<label for="auth-lost-login"><?php esc_html_e( 'Email или логин', 'evgagarina' ); ?></label>
				<input id="auth-lost-login" name="user_login" type="text" autocomplete="username" autocapitalize="none" spellcheck="false" required aria-describedby="auth-lost-error">
			</div>
			<p class="auth-modal__error" id="auth-lost-error" role="alert" hidden></p>
			<button type="submit" class="auth-modal__submit"><?php esc_html_e( 'Получить ссылку', 'evgagarina' ); ?></button>
		</div>
		<p class="auth-modal__success" id="auth-lost-success" role="status" tabindex="-1" hidden></p>
		<button type="button" class="auth-modal__back" data-auth-view="login">
			<?php echo evg_icon( 'chevron-left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php esc_html_e( 'Вернуться ко входу', 'evgagarina' ); ?>
		</button>
	</form>
</dialog>
