<?php
/**
 * Анкета: имя, email, смена пароля.
 *
 * @package evgagarina
 * @version 9.7.0
 *
 * @var WP_User $user
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>
<section aria-labelledby="h-profile">
	<h2 id="h-profile" class="acc-h2"><?php esc_html_e( 'Анкета', 'evgagarina' ); ?></h2>
	<form class="acc-box acc-form woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<label for="account_first_name"><?php esc_html_e( 'Имя', 'evgagarina' ); ?>
			<input type="text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" required>
		</label>
		<label for="account_last_name"><?php esc_html_e( 'Фамилия', 'evgagarina' ); ?>
			<input type="text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>">
		</label>
		<label for="account_display_name"><?php esc_html_e( 'Отображаемое имя', 'evgagarina' ); ?>
			<input type="text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" required>
		</label>
		<label for="account_email"><?php esc_html_e( 'Email', 'evgagarina' ); ?>
			<input type="email" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" required>
		</label>

		<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

		<fieldset class="acc-fieldset">
			<legend><?php esc_html_e( 'Смена пароля', 'evgagarina' ); ?></legend>
			<label for="password_current"><?php esc_html_e( 'Текущий пароль', 'evgagarina' ); ?>
				<input type="password" name="password_current" id="password_current" autocomplete="current-password" placeholder="<?php esc_attr_e( 'Оставьте пустым, чтобы не менять', 'evgagarina' ); ?>">
			</label>
			<label for="password_1"><?php esc_html_e( 'Новый пароль', 'evgagarina' ); ?>
				<input type="password" name="password_1" id="password_1" autocomplete="new-password">
			</label>
			<label for="password_2"><?php esc_html_e( 'Повторите новый пароль', 'evgagarina' ); ?>
				<input type="password" name="password_2" id="password_2" autocomplete="new-password">
			</label>
		</fieldset>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="checkout-submit acc-btn" name="save_account_details" value="<?php esc_attr_e( 'Сохранить изменения', 'evgagarina' ); ?>"><?php esc_html_e( 'Сохранить изменения', 'evgagarina' ); ?></button>
		<input type="hidden" name="action" value="save_account_details">

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</section>
<?php
do_action( 'woocommerce_after_edit_account_form' );
