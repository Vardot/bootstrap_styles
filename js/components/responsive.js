/**
 * @file
 * Behaviors Bootstrap styles responsive scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.bootstrapStylesResponsive = {
    attach: function (context) {

      // Allows us to attach tooltips to radio option labels.
      $("svg[data-bs-tooltip-label]", context).once('bs-svg-tooltips').each(function (e) {
        var placement = $(this).attr('data-bs-tooltip-placement') ? $(this).attr('data-bs-tooltip-placement') : 'top';
        var label = $(this).attr('data-bs-tooltip-label') ? $(this).attr('data-bs-tooltip-label') : '';
        $(this).after('<div class="bs_tooltip" data-placement="' + placement + '" role="tooltip">' + label + '</div>');
      });

    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
