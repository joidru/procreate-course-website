<?php
/**
 * Подвал сайта.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$evg_email = evg_mod( 'evg_contact_email' );
$evg_legal = evg_mod( 'evg_legal_line' );
?>
<footer id="contacts" class="border-t border-border bg-background">
	<div class="mx-auto max-w-6xl px-5 py-12 md:px-8">
		<div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
			<div class="max-w-xs">
				<p class="font-display text-lg font-bold text-foreground"><?php bloginfo( 'name' ); ?></p>
				<p class="mt-2 text-sm leading-relaxed text-muted-foreground"><?php echo esc_html( evg_mod( 'evg_footer_tagline' ) ); ?></p>
			</div>

			<?php if ( $evg_email ) : ?>
				<div class="flex flex-col gap-2 text-sm">
					<p class="font-medium text-foreground"><?php esc_html_e( 'По вопросам покупки и оплаты', 'evgagarina' ); ?></p>
					<a href="<?php echo esc_url( 'mailto:' . antispambot( $evg_email ) ); ?>" class="text-muted-foreground transition-colors hover:text-foreground"><?php echo esc_html( antispambot( $evg_email ) ); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) || evg_default_menu_links( 'footer' ) ) : ?>
				<nav class="flex flex-col gap-2 text-sm" aria-label="<?php esc_attr_e( 'Документы', 'evgagarina' ); ?>">
					<p class="font-medium text-foreground"><?php esc_html_e( 'Документы', 'evgagarina' ); ?></p>
					<?php evg_menu( 'footer', 'text-muted-foreground transition-colors hover:text-foreground' ); ?>
				</nav>
			<?php endif; ?>
		</div>

		<div class="mt-10 flex flex-col gap-1 border-t border-border pt-6 text-xs text-muted-foreground md:flex-row md:items-center md:justify-between">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( $evg_legal ? $evg_legal : get_bloginfo( 'name' ) ); ?></p>
			<p><?php esc_html_e( 'Все права защищены.', 'evgagarina' ); ?></p>
		</div>
	</div>
</footer>
</div>
<?php
if ( ! is_user_logged_in() ) {
	get_template_part( 'template-parts/auth-modal' );
}
wp_footer();
?>
</body>
</html>
