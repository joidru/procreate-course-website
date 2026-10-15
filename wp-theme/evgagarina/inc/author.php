<?php
/**
 * Профиль автора: соцсети, должность и фото в профиле пользователя WordPress.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

function evg_author_networks() {
	return apply_filters(
		'evg_author_networks',
		array(
			'telegram'  => 'Telegram',
			'vk'        => 'ВКонтакте',
			'youtube'   => 'YouTube',
			'instagram' => 'Instagram',
			'pinterest' => 'Pinterest',
		)
	);
}

/**
 * Поля соцсетей в разделе «Контактная информация» профиля.
 */
function evg_user_contactmethods( $methods ) {
	foreach ( evg_author_networks() as $key => $label ) {
		$methods[ 'evg_' . $key ] = $label . ' (URL)';
	}
	return $methods;
}
add_filter( 'user_contactmethods', 'evg_user_contactmethods' );

/**
 * Дополнительные поля профиля: подпись и фото автора.
 */
function evg_user_profile_fields( $user ) {
	if ( ! current_user_can( 'edit_user', $user->ID ) ) {
		return;
	}
	?>
	<h2><?php esc_html_e( 'Блок автора в статьях', 'evgagarina' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="evg_author_eyebrow"><?php esc_html_e( 'Подпись над именем', 'evgagarina' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" name="evg_author_eyebrow" id="evg_author_eyebrow" value="<?php echo esc_attr( get_user_meta( $user->ID, 'evg_author_eyebrow', true ) ); ?>" placeholder="<?php esc_attr_e( 'Автор статьи', 'evgagarina' ); ?>">
			</td>
		</tr>
		<tr>
			<th><label for="evg_author_photo"><?php esc_html_e( 'Фото автора (ID вложения из медиатеки)', 'evgagarina' ); ?></label></th>
			<td>
				<input type="number" min="0" class="small-text" name="evg_author_photo" id="evg_author_photo" value="<?php echo esc_attr( (int) get_user_meta( $user->ID, 'evg_author_photo', true ) ); ?>">
				<p class="description"><?php esc_html_e( 'Если не указано — используется Gravatar. Текст «Об авторе» берётся из поля «Биографическая информация».', 'evgagarina' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'evg_user_profile_fields' );
add_action( 'edit_user_profile', 'evg_user_profile_fields' );

function evg_save_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}
	check_admin_referer( 'update-user_' . $user_id );

	if ( isset( $_POST['evg_author_eyebrow'] ) ) {
		update_user_meta( $user_id, 'evg_author_eyebrow', sanitize_text_field( wp_unslash( $_POST['evg_author_eyebrow'] ) ) );
	}
	if ( isset( $_POST['evg_author_photo'] ) ) {
		update_user_meta( $user_id, 'evg_author_photo', absint( $_POST['evg_author_photo'] ) );
	}
}
add_action( 'personal_options_update', 'evg_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'evg_save_user_profile_fields' );

/**
 * Фото автора: вложение из профиля либо аватар WordPress.
 */
function evg_author_photo_url( $user_id, $size = 176 ) {
	$attachment_id = (int) get_user_meta( $user_id, 'evg_author_photo', true );
	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'evg-square' );
		if ( $url ) {
			return $url;
		}
	}
	return get_avatar_url( $user_id, array( 'size' => $size ) );
}
