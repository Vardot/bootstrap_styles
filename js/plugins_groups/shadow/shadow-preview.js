/**
 * @file
 * Behaviors shadow plugin group.
 */

(function ($, Drupal, once) {
  // Shadow preview box.
  Drupal.behaviors.shadowPreview = {
    attach(context) {
      const previewElement = $(
        '.bs-shadow-preview [data-bs-element="bs_preview-element"]',
      );

      // Refresh preview classes.
      function refreshShadowClasses(el) {
        let shadow_classes = '';

        // Setup our shadow classes.
        $('input.bs-field-box-shadow').each(function () {
          if ($(this).is(':checked') && $(this).val() != '_none') {
            shadow_classes += `${$(this).val()} `;
          }
        });

        // Remove all classes.
        previewElement.removeClass();

        // Then add our selected class.
        previewElement.addClass(shadow_classes);
      }

      refreshShadowClasses();

      // Refresh the box shadow classes on change.
      const input_triggers = ['input[class^="bs-field-box-shadow"]'];

      $.each(input_triggers, function (index, value) {
        $(value, context).on('change', function () {
          refreshShadowClasses();
        });
      });

      // Toggle our bg color mode.
      function togglePreviewBackgroundColor(el) {
        const previewBgElement = el.closest(
          '[data-bs-element="bs_preview-box"]',
        );
        previewBgElement.attr(
          'data-bs-mode',
          previewBgElement.attr('data-bs-mode') === 'light' ? 'dark' : 'light',
        );
      }

      $('.bs-shadow-preview .bs-toggle-switch', context).on(
        'change',
        function () {
          togglePreviewBackgroundColor($(this));
        },
      );
    },
  };
})(jQuery, Drupal, once);
