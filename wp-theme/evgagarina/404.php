
<?php
/**
 * Страница 404.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content" class="result-wrap error-404">
    <section class="result-card" aria-labelledby="nf-title">

        <span class="error-404__badge">404</span>

        <h1 id="nf-title" class="result-title">
            <?php esc_html_e( 'Страница не найдена', 'evgagarina' ); ?>
        </h1>

        <p class="result-text">
            <?php esc_html_e(
                'Возможно, ссылка устарела или страница была удалена. Вы можете вернуться к курсам или перейти в блог.',
                'evgagarina'
            ); ?>
        </p>

        <div class="result-actions">
            <a
                href="<?php echo esc_url( home_url( '/#courses' ) ); ?>"
                class="checkout-submit result-btn"
            >
                <?php esc_html_e( 'Смотреть курсы', 'evgagarina' ); ?>
            </a>

            <a
                href="<?php echo esc_url( evg_blog_url() ); ?>"
                class="result-btn-outline"
            >
                <?php esc_html_e( 'Перейти в блог', 'evgagarina' ); ?>
            </a>
        </div>

    </section>
</main>

<?php get_footer(); ?>
