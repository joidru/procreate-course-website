<?php
/**
 * Шапка сайта.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$evg_logged_in  = is_user_logged_in();
$evg_on_account = function_exists( 'is_account_page' ) && is_account_page();
$evg_link_class = 'text-sm text-muted-foreground transition-colors hover:text-foreground';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="dark bg-background">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link sr-only" href="#content"><?php esc_html_e( 'Перейти к содержимому', 'evgagarina' ); ?></a>
<div class="min-h-screen bg-background">
<header class="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur-md">
	<div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 md:px-8">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 font-display text-base font-bold tracking-tight text-foreground" rel="home">
			<img src="<?php echo esc_url( evg_logo_url() ); ?>" width="40" height="40" alt="" class="site-logo">
			<span><?php bloginfo( 'name' ); ?></span>
		</a>

		<nav class="hidden items-center gap-8 md:flex" aria-label="<?php esc_attr_e( 'Основная навигация', 'evgagarina' ); ?>">
			<?php evg_menu( 'primary', $evg_link_class ); ?>
			<?php if ( $evg_logged_in ) : ?>
				<a href="<?php echo esc_url( evg_account_url() ); ?>" class="acc-userchip"<?php echo $evg_on_account ? ' aria-current="page"' : ''; ?>>
					<span class="acc-avatar acc-avatar-sm" aria-hidden="true"><?php echo esc_html( evg_user_initial() ); ?></span>
					<span><?php echo esc_html( evg_user_first_name() ); ?></span>
				</a>
			<?php endif; ?>
		</nav>

		<div class="flex items-center gap-1 md:gap-2">
			<?php if ( ! $evg_on_account ) : ?>
				<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="hidden rounded-full bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground transition-opacity hover:opacity-90 md:inline-flex"><?php esc_html_e( 'Выбрать курс', 'evgagarina' ); ?></a>
			<?php endif; ?>

			<?php if ( $evg_logged_in ) : ?>
				<a href="<?php echo esc_url( evg_account_url() ); ?>" class="acc-avatar acc-avatar-sm md:hidden" aria-label="<?php esc_attr_e( 'Личный кабинет', 'evgagarina' ); ?>"><?php echo esc_html( evg_user_initial() ); ?></a>
			<?php else : ?>
				<a href="<?php echo esc_url( evg_account_url() ); ?>" class="login-btn" aria-label="<?php esc_attr_e( 'Войти в личный кабинет', 'evgagarina' ); ?>">
					<?php echo evg_icon( 'user', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="login-btn__label"><?php esc_html_e( 'Войти', 'evgagarina' ); ?></span>
				</a>
			<?php endif; ?>

			<button aria-controls="mobile-nav" id="menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Открыть меню', 'evgagarina' ); ?>" aria-expanded="false" class="flex h-11 w-11 items-center justify-center rounded-full text-foreground md:hidden">
				<?php echo evg_icon( 'menu', 24, 'h-5 w-5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>

	<nav aria-label="<?php esc_attr_e( 'Мобильная навигация', 'evgagarina' ); ?>" hidden id="mobile-nav" class="mobile-nav">
		<?php evg_menu( 'primary', 'text-sm text-muted-foreground' ); ?>
		<?php if ( ! $evg_on_account ) : ?>
			<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="mobile-cta"><?php esc_html_e( 'Выбрать курс', 'evgagarina' ); ?></a>
		<?php endif; ?>
	</nav>
</header>
