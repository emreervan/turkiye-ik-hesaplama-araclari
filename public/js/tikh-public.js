/**
 * Türkiye İK Hesaplama Araçları - Public JavaScript
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 *
 * All selectors use unique prefix: tikh2026-
 */

(function($) {
	'use strict';

	/**
	 * TIKH2026 namespace
	 */
	window.TIKH2026 = window.TIKH2026 || {};

	/**
	 * Format number as Turkish currency (1.234,56)
	 *
	 * @param {string} value Input value
	 * @return {string} Formatted value
	 */
	TIKH2026.formatCurrency = function(value) {
		// Remove non-numeric characters except comma
		var cleaned = value.replace(/[^\d,]/g, '');
		var parts = cleaned.split(',');

		// Format integer part with thousand separators
		if (parts[0]) {
			parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		}

		// Return with decimal part if exists
		return parts.join(',');
	};

	/**
	 * Initialize currency input formatting
	 */
	TIKH2026.initCurrencyInputs = function() {
		$(document).on('input', '.tikh2026-currency-input', function() {
			var formatted = TIKH2026.formatCurrency(this.value);
			this.value = formatted;
		});
	};

	/**
	 * Initialize Bordro calculator
	 */
	TIKH2026.initBordro = function() {
		var $container = $('.tikh2026-bordro-wrap');
		if (!$container.length) {
			return;
		}

		var $select = $container.find('#tikh2026_bordro_turu');
		var $label = $container.find('#tikh2026_bordro_lbl');
		var $emekli = $container.find('#tikh2026_bordro_emekli');
		var $hazine = $container.find('#tikh2026_bordro_hazine');

		// Change label based on calculation type
		$select.on('change', function() {
			if (this.value === 'brut_net') {
				$label.text(tikhPublic.i18n.brutLabel);
			} else {
				$label.text(tikhPublic.i18n.netLabel);
			}
		});

		// Disable treasury support for retired employees
		$emekli.on('change', function() {
			if (this.checked) {
				$hazine.val('0').prop('disabled', true);
			} else {
				$hazine.prop('disabled', false).val('0.05');
			}
		});
	};

	/**
	 * Initialize Zam calculator
	 */
	TIKH2026.initZam = function() {
		var $container = $('.tikh2026-zam-wrap');
		if (!$container.length) {
			return;
		}

		var $modInput = $container.find('#tikh2026_zam_mod');
		var $modBtns = $container.find('.tikh2026-modbtn');
		var $oranGrp = $container.find('#tikh2026_zam_oran_grp');
		var $zamliGrp = $container.find('#tikh2026_zam_zamli_grp');

		// Mode toggle
		$modBtns.on('click', function() {
			var mode = $(this).data('mode');
			$modInput.val(mode);

			$modBtns.removeClass('active');
			$(this).addClass('active');

			if (mode === 'yuzde') {
				$oranGrp.removeClass('hidden');
				$zamliGrp.addClass('hidden');
			} else {
				$oranGrp.addClass('hidden');
				$zamliGrp.removeClass('hidden');
			}
		});
	};

	/**
	 * Initialize Gelir Vergisi calculator
	 */
	TIKH2026.initGelirVergisi = function() {
		var $container = $('.tikh2026-gv-wrap');
		if (!$container.length) {
			return;
		}

		var $tip = $container.find('#tikh2026_gv_tip');
		var $istisnaWrap = $container.find('#tikh2026_gv_istisna_wrap');
		var $istisna = $container.find('#tikh2026_gv_istisna');

		// Disable exemption for non-wage earners
		$tip.on('change', function() {
			if (this.value === 'diger') {
				$istisnaWrap.css('opacity', '0.5');
				$istisna.prop('checked', false).prop('disabled', true);
			} else {
				$istisnaWrap.css('opacity', '1');
				$istisna.prop('disabled', false);
			}
		});
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		TIKH2026.initCurrencyInputs();
		TIKH2026.initBordro();
		TIKH2026.initZam();
		TIKH2026.initGelirVergisi();
	});

})(jQuery);
