/**
 * @file
 * Behaviors Bootstrap styles themes overrides scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.bootstrapStylesThemesOverrides = {
    attach: function (context) {
      // Remove custom-control class from Barrio theme.
      $(".bs_tab-pane--appearance input:radio", context).once('bs-themes-overrides').each(function () {
        $(this).parent().removeClass('custom-control custom-radio');
        $(this).removeClass('custom-control-input');
        $(this).next('label').removeClass('custom-control-label');
      });
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
