/**
 * BulkBoost — variable product bundles (Pro).
 *
 * Each offer tier renders one attribute picker per item in the bundle, so a
 * customer buying three can choose a different variation for each. The first
 * item's choices are mirrored into WooCommerce's own attribute selects, which
 * leaves core in charge of resolving the variation, showing its price and
 * enabling the add-to-cart button. The remaining items ride along in the form
 * and are added server-side.
 *
 * Config arrives via wp_localize_script as `BulkBoostBundle`:
 *   { variations: [{id, attributes:{attribute_pa_size: 'small'}, in_stock}],
 *     i18n: {incomplete, unavailable} }
 */
(function ($) {
    'use strict';

    var CFG = window.BulkBoostBundle || {};

    $(function () {
        var $block = $('.custom-quantity-block.bb-variable');
        if (!$block.length) {
            return;
        }

        var $form = $block.closest('form.variations_form');
        if (!$form.length) {
            return;
        }

        var $tierInput = $block.find('[data-bulkboost-tier-input]');
        var $notice = $('<div class="bulkboost-bundle-notice" role="alert" hidden></div>').appendTo($block);
        var variations = CFG.variations || [];

        // Core's own attribute table is redundant here — every item has its own
        // picker — but it stays in the DOM and keeps doing its job for item #1.
        $form.addClass('bb-bundle-active');

        function message(text) {
            if (!text) {
                $notice.attr('hidden', true).text('');
                return;
            }
            $notice.text(text).removeAttr('hidden');
        }

        /**
         * Resolves a set of chosen attributes to one of this product's
         * variations. "Any" variations (stored as an empty value) match
         * whatever the customer picked.
         */
        function findVariation(chosen) {
            var i, v, name, want, has, ok;

            for (i = 0; i < variations.length; i++) {
                v = variations[i];
                ok = true;

                for (name in chosen) {
                    if (!Object.prototype.hasOwnProperty.call(chosen, name)) {
                        continue;
                    }
                    want = chosen[name];
                    has = v.attributes[name];

                    if (has !== '' && has !== undefined && has !== want) {
                        ok = false;
                        break;
                    }
                }

                if (ok) {
                    return v;
                }
            }

            return null;
        }

        /** Collects one unit's selections, keyed the way variations store them. */
        function unitSelection($unit) {
            var chosen = {};
            var complete = true;

            $unit.find('.bulkboost-unit-select').each(function () {
                var name = $(this).data('attribute_name');
                var value = $(this).val();

                if (!value) {
                    complete = false;
                }
                chosen[name] = value;
            });

            return { chosen: chosen, complete: complete };
        }

        function activeUnits() {
            return $block.find('.bulkboost-swatch.active').find('[data-bulkboost-units]');
        }

        /** Mirrors item #1 into core's selects so WooCommerce resolves it. */
        function syncFirstUnit() {
            var $first = activeUnits().find('.bulkboost-unit').first();
            if (!$first.length) {
                return;
            }

            $first.find('.bulkboost-unit-select').each(function () {
                var name = $(this).data('attribute_name');
                var $native = $form.find('select[name="' + name + '"]');

                if ($native.length && $native.val() !== $(this).val()) {
                    $native.val($(this).val()).trigger('change');
                }
            });
        }

        /**
         * Marks units whose combination does not exist and reports whether the
         * whole bundle is ready to be added.
         */
        function refresh() {
            var $units = activeUnits();
            var ready = $units.length > 0;
            var incomplete = false;
            var unavailable = false;

            $units.find('.bulkboost-unit').each(function () {
                var $unit = $(this);
                var selection = unitSelection($unit);
                var variation = selection.complete ? findVariation(selection.chosen) : null;
                var usable = !!(variation && variation.in_stock);

                $unit.toggleClass('bb-unit-invalid', selection.complete && !usable);

                if (!selection.complete) {
                    incomplete = true;
                } else if (!usable) {
                    unavailable = true;
                }

                if (!usable) {
                    ready = false;
                }
            });

            syncFirstUnit();

            // Every unit is its own cart line, so core only ever adds one here.
            $form.find('input.qty').val(1);

            if (ready) {
                message('');
            }

            return { ready: ready, incomplete: incomplete, unavailable: unavailable };
        }

        /** Shows only the active tier's pickers and records which tier that is. */
        function selectTier() {
            var $active = $block.find('.bulkboost-swatch.active');

            $block.find('[data-bulkboost-units]').removeClass('is-active');
            $active.find('[data-bulkboost-units]').addClass('is-active');

            if ($tierInput.length) {
                $tierInput.val($block.find('.bulkboost-swatch').index($active));
            }

            message('');
            refresh();
        }

        function blockedMessage(state) {
            if (state.unavailable) {
                return (CFG.i18n && CFG.i18n.unavailable) || 'That combination is unavailable. Please pick different options.';
            }
            return (CFG.i18n && CFG.i18n.incomplete) || 'Please choose the options for every item in this bundle.';
        }

        $block.on('change', '.bulkboost-unit-select', refresh);

        // The core script toggles `.active` on click; this runs afterwards.
        $block.on('click', '.bulkboost-swatch', function () {
            window.setTimeout(selectTier, 0);
        });

        // Core adds variable products over AJAX from the button's own click
        // handler, so a submit-only guard would never run. Capturing on the way
        // down stops that handler before it fires, and still covers a plain
        // form submit on themes that do not take the AJAX path.
        $form[0].addEventListener('click', function (event) {
            if (!event.target.closest('.single_add_to_cart_button')) {
                return;
            }

            var state = refresh();
            if (state.ready) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            message(blockedMessage(state));
        }, true);

        $form.on('submit', function (event) {
            var state = refresh();
            if (state.ready) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            message(blockedMessage(state));
        });

        selectTier();
    });
})(jQuery);
