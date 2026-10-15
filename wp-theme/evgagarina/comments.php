<?php
/**
 * Нативные комментарии WordPress в оформлении темы.
 *
 * @package evgagarina
 */
defined( 'ABSPATH' ) || exit;
if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="evg-comments" aria-label="<?php esc_attr_e( 'Комментарии', 'evgagarina' ); ?>">
    <?php if ( have_comments() ) : ?>
        <h2 id="comments-title" class="evg-comments__title">
            <?php
            $evg_count = (int) get_comments_number();
            echo esc_html( $evg_count . ' ' . evg_plural( $evg_count, array( 'комментарий', 'комментария', 'комментариев' ) ) );
            ?>
        </h2>
        <ol class="evg-comments__list">
            <?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) ); ?>
        </ol>
        <div class="evg-comments__navigation"><?php the_comments_navigation(); ?></div>
    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
        <?php
        $evg_commenter = wp_get_current_commenter();
        $evg_required = (bool) get_option( 'require_name_email' );
        comment_form(
            array(
                'class_form'         => 'evg-comments__form',
                'class_submit'       => 'evg-comments__submit',
                'title_reply'        => __( 'Оставить комментарий', 'evgagarina' ),
                'title_reply_to'     => __( 'Ответить %s', 'evgagarina' ),
                'cancel_reply_link'  => __( 'Отменить ответ', 'evgagarina' ),
                'label_submit'       => __( 'Отправить комментарий', 'evgagarina' ),
                'title_reply_before' => '<h2 id="reply-title" class="evg-comments__title">',
                'title_reply_after'  => '</h2>',
                'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Комментарий', 'evgagarina' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" rows="5" maxlength="65525" required></textarea></p>',
                'fields'             => array(
                    'author' => '<p class="comment-form-author"><label for="author">' . esc_html__( 'Имя', 'evgagarina' ) . ( $evg_required ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $evg_commenter['comment_author'] ) . '" autocomplete="name" maxlength="245"' . ( $evg_required ? ' required' : '' ) . '></p>',
                    'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'evgagarina' ) . ( $evg_required ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $evg_commenter['comment_author_email'] ) . '" autocomplete="email" maxlength="100"' . ( $evg_required ? ' required' : '' ) . '></p>',
                    'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Сайт', 'evgagarina' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $evg_commenter['comment_author_url'] ) . '" autocomplete="url" maxlength="200"></p>',
                ),
            )
        );
        ?>
    <?php elseif ( ! have_comments() ) : ?>
        <p class="evg-comments__closed"><?php esc_html_e( 'Комментарии закрыты.', 'evgagarina' ); ?></p>
    <?php endif; ?>
</section>
