<?php

/**
 * Страница после оплаты: «Спасибо» (payment-success.html)
 * или «Оплата не прошла» (payment-failed.html).
 *
 * @package evgagarina
 * @version 8.1.0
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;

$evg_email = evg_mod('evg_contact_email');
?>
<div class="result-wrap woocommerce-order">
	<?php
	if (! $order) :
		wc_get_template('checkout/order-received.php', array('order' => false));
		echo '</div>';
		return;
	endif;

	do_action('woocommerce_before_thankyou', $order->get_id());

	if ($order->has_status('failed')) :
	?>
		<section class="result-card" aria-labelledby="result-title">
			<div class="result-icon result-icon-failed" aria-hidden="true"><?php echo evg_icon('x', 28); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
																			?></div>
			<p class="result-eyebrow"><?php esc_html_e('Платёж не завершён', 'evgagarina'); ?></p>
			<h1 id="result-title" class="result-title"><?php esc_html_e('Оплата не прошла', 'evgagarina'); ?></h1>
			<p class="result-text"><?php esc_html_e('Деньги не списаны или будут автоматически возвращены банком. Ваш заказ сохранён, его можно оплатить ещё раз.', 'evgagarina'); ?></p>

			<div class="result-reasons">
				<p class="result-step-title"><?php esc_html_e('Частые причины', 'evgagarina'); ?></p>
				<ul>
					<li><?php esc_html_e('Включён VPN — банк может отклонить платёж. Выключите его и повторите оплату.', 'evgagarina'); ?></li>
					<li><?php esc_html_e('Недостаточно средств или превышен лимит по карте.', 'evgagarina'); ?></li>
					<li><?php esc_html_e('Платёж не подтверждён кодом из SMS или в приложении банка.', 'evgagarina'); ?></li>
					<li><?php esc_html_e('Банк отклонил операцию — попробуйте другую карту или СБП.', 'evgagarina'); ?></li>
				</ul>
			</div>

			<div class="result-actions">
				<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="checkout-submit result-btn"><?php esc_html_e('Попробовать снова', 'evgagarina'); ?></a>
				<a href="<?php echo esc_url(evg_section_url('courses')); ?>" class="result-btn-outline"><?php esc_html_e('Вернуться к каталогу', 'evgagarina'); ?></a>
			</div>
			<?php if ($evg_email) : ?>
				<p class="checkout-legal">
					<?php esc_html_e('Не получается оплатить? Напишите на', 'evgagarina'); ?>
					<a href="<?php echo esc_url('mailto:' . $evg_email); ?>"><?php echo esc_html($evg_email); ?></a>,
					<?php esc_html_e('и мы предложим другой способ.', 'evgagarina'); ?>
				</p>
			<?php endif; ?>
		</section>
	<?php
	else :
		$evg_paid = $order->is_paid();
	?>
		<section class="result-card" aria-labelledby="result-title">
			<div class="result-icon result-icon-success" aria-hidden="true"><?php echo evg_icon('check', 28); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
																			?></div>
			<p class="result-eyebrow">
				<?php
				echo esc_html(
					$evg_paid
						/* translators: %s: номер заказа */
						? sprintf(__('Заказ №%s оплачен', 'evgagarina'), $order->get_order_number())
						/* translators: %s: номер заказа */
						: sprintf(__('Заказ №%s принят', 'evgagarina'), $order->get_order_number())
				);
				?>
			</p>
			<h1 id="result-title" class="result-title"><?php esc_html_e('Спасибо за покупку!', 'evgagarina'); ?></h1>
			<p class="result-text">
				<?php
				if ($evg_paid) {
					/* translators: %s: email покупателя */
					printf(esc_html__('Оплата прошла успешно. Мы отправили письмо с доступом к материалам на %s.', 'evgagarina'), '<strong>' . esc_html($order->get_billing_email()) . '</strong>');
				} else {
					esc_html_e('Заказ создан. Доступ к материалам откроется сразу после подтверждения оплаты — мы пришлём письмо.', 'evgagarina');
				}
				?>
			</p>

			<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>

			<ol class="result-steps">
				<li>
					<span class="result-step-num" aria-hidden="true">1</span>
					<div>
						<p class="result-step-title"><?php esc_html_e('Проверьте почту', 'evgagarina'); ?></p>
						<p class="result-step-text"><?php esc_html_e('Письмо приходит в течение нескольких минут. Если его нет во «Входящих», загляните в «Спам».', 'evgagarina'); ?></p>
					</div>
				</li>
				<li>
					<span class="result-step-num" aria-hidden="true">2</span>
					<div>
						<p class="result-step-title"><?php esc_html_e('Откройте доступ', 'evgagarina'); ?></p>
						<p class="result-step-text"><?php esc_html_e('Курсы и файлы также доступны в личном кабинете в разделах «Мои курсы» и «Загрузки».', 'evgagarina'); ?></p>
					</div>
				</li>
				<li>
					<span class="result-step-num" aria-hidden="true">3</span>
					<div>
						<p class="result-step-title"><?php esc_html_e('Начните рисовать', 'evgagarina'); ?></p>
						<p class="result-step-text"><?php esc_html_e('Установите материалы в Procreate на iPad и приступайте к первому уроку.', 'evgagarina'); ?></p>
					</div>
				</li>
			</ol>

			<div class="result-actions">
				<a href="<?php echo esc_url(is_user_logged_in() ? evg_account_url() : evg_section_url('courses')); ?>" class="checkout-submit result-btn">
					<?php is_user_logged_in() ? esc_html_e('В личный кабинет', 'evgagarina') : esc_html_e('Перейти к каталогу', 'evgagarina'); ?>
				</a>
				<a href="<?php echo esc_url(home_url('/')); ?>" class="result-btn-outline"><?php esc_html_e('На главную', 'evgagarina'); ?></a>
			</div>
			<?php if ($evg_email) : ?>
				<p class="checkout-legal">
					<?php esc_html_e('Письмо так и не пришло? Напишите на', 'evgagarina'); ?>
					<a href="<?php echo esc_url('mailto:' . $evg_email); ?>"><?php echo esc_html($evg_email); ?></a>,
					<?php esc_html_e('и мы поможем.', 'evgagarina'); ?>
				</p>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</div>