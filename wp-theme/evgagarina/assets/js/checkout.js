/**
 * Купон в правой колонке чекаута.
 *
 * Поле купона находится внутри формы checkout (вложенные формы запрещены),
 * поэтому применяем его тем же AJAX-запросом, что и стандартная форма
 * купона WooCommerce, и затем просим WooCommerce пересчитать заказ.
 */
(function ($) {
	'use strict';

	if (typeof wc_checkout_params === 'undefined') {
		return;
	}

	var i18n = window.evgCheckout || {};

	function setStatus(message, isError) {
		var status = document.getElementById('coupon-status');
		if (!status) return;
		status.textContent = message || '';
		status.classList.toggle('checkout-error', !!isError);
	}

	function applyCoupon() {
		var input = document.getElementById('evg-coupon');
		var button = document.getElementById('coupon-apply');
		if (!input || !button) return;

		var code = input.value.trim();
		if (!code) {
			setStatus(i18n.emptyCoupon, true);
			input.focus();
			return;
		}

		button.disabled = true;
		setStatus('');

		$.ajax({
			type: 'POST',
			url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
			data: {
				security: wc_checkout_params.apply_coupon_nonce,
				coupon_code: code,
				billing_email: $('#billing_email').val() || ''
			},
			dataType: 'html'
		})
			.done(function (html) {
				var tmp = document.createElement('div');
				tmp.innerHTML = html;
				var isError = !!tmp.querySelector('.woocommerce-error, .is-error');
				setStatus(tmp.textContent.trim(), isError);
				if (!isError) {
					$(document.body).trigger('applied_coupon_in_checkout', [code]);
					$(document.body).trigger('update_checkout', { update_shipping_method: false });
				}
			})
			.fail(function () {
				setStatus(i18n.network, true);
			})
			.always(function () {
				button.disabled = false;
			});
	}

	$(document.body)
		.on('click', '#coupon-toggle', function () {
			var panel = document.getElementById('coupon-panel');
			if (!panel) return;
			var open = this.getAttribute('aria-expanded') === 'true';
			this.setAttribute('aria-expanded', String(!open));
			panel.hidden = open;
			if (!open) {
				var input = document.getElementById('evg-coupon');
				if (input) input.focus();
			}
		})
		.on('click', '#coupon-apply', applyCoupon)
		.on('keydown', '#evg-coupon', function (event) {
			if (event.key !== 'Enter' || event.isComposing || event.keyCode === 229) return;
			event.preventDefault();
			applyCoupon();
		});
})(jQuery);
