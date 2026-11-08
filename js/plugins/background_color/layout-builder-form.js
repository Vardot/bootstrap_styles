/**
 * @file
 * Behaviors Background Color plugin layout builder form scripts.
 */

/* global rgb2hex, getContrast */

(function ($, Drupal, once) {
  // Background color.
  Drupal.behaviors.backgroundColorLayoutBuilderForm = {
    attach(context) {
      once(
        'blb_bg-color',
        '.fieldgroup.field-background-color input[type=radio]',
        context,
      ).forEach(function (value, i) {
        if ($(value).val() != '_none') {
          $(value).next('label').addClass($(value).val());

          // Set a contrast class so we can see our checkmarks on light vs. dark backgrounds.s.
          const bgColor = $(value).next('label').css('background-color');
          const bgColorHex = rgb2hex(bgColor);
          const bgColorContrast = getContrast(bgColorHex);
          $(value).next('label').addClass(`bs_yiq-${bgColorContrast}`);
        }
      });

      $(
        '.fieldgroup.field-background-color .fieldset-wrapper label',
        context,
      ).on('click', function () {
        $(this)
          .parents('.fieldset-wrapper')
          .find('label')
          .removeClass('active');
        $(this)
          .parents('.fieldset-wrapper')
          .addClass('style-selected')
          .find('input')
          .prop('checked', false);
        // Temp comment the following line because of conflict with live preview.
        // $(this).parent().find('input').prop('checked', true);

        if ($(this).hasClass('_none')) {
          $(this).parents('.fieldset-wrapper').removeClass('style-selected');
        }
      });

      // Custom solution for bootstrap 3 & Bario drupal theme issues.
      $(
        '.fieldgroup.field-background-color .fieldset-wrapper input:radio',
        context,
      ).each(function () {
        $(this).closest('.radio').find('label').addClass($(this).val());
        const checked = $(this).prop('checked');
        if (typeof checked !== typeof undefined && checked !== false) {
          $(this).closest('.radio').find('label').addClass('active');
        }
      });
    },
  };
})(jQuery, Drupal, once);
