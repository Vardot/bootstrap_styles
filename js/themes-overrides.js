/**
 * @file
 * Behaviors Bootstrap styles themes overrides scripts.
 */

(function ($, Drupal, once) {
  Drupal.behaviors.bootstrapStylesThemesOverrides = {
    attach(context) {
      // Layout builder modal
      // @todo: we need to add this class somewhere else
      if ($('#layout-builder-modal').length) {
        $(document).ajaxComplete(function () {
          $('#layout-builder-modal')
            .parent()
            .addClass('ui-layout-builder-modal');
        });
      }

      // Remove custom-control class from Barrio theme.
      once(
        'bs-themes-overrides',
        '.bs_tab-pane--appearance input[type=radio]',
        context,
      ).forEach(function (value, i) {
        $(value).parent().removeClass('custom-control custom-radio');
        $(value).removeClass('custom-control-input');
        $(value).next('label').removeClass('custom-control-label');
      });
    },
  };
})(jQuery, Drupal, once);
