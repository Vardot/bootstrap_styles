/**
 * @file
 * Behaviors Border plugin layout builder form scripts.
 */

(function ($, Drupal, once) {
  "use strict";
  
  // Border.
  Drupal.behaviors.borderLayoutBuilderForm = {
    attach: function (context) {
      // The default border color.
      once('blb_border','input.bs-field-border-color', context).forEach(function (value,i) {
      var border_color = '';
        if ($(value).val() !='_none' && typeof $(value).next('label').css('border-color') != 'undefined') {
          border_color = $(value).next('label').css('border-color');
          $(value).next('label').attr('style', 'background-color: ' + border_color + ' !important; border-color: white !important;');
        }
      });

      // Assign border style.
      var directions = ['left', 'top', 'right', 'bottom'];
      // Loop through the directions.
      for (var i = 0; i < directions.length; i++) {
        once('blb_border','input.bs-field-border-style-' + directions[i], context).forEach(function (value,i) {
          var border_style = '';
          if ($(value).val() !='_none' && typeof $(value).next('label').css('border-style') != 'undefined') {
            border_style = $(value).next('label').css('border-' + directions[i] + '-style');
            $(value).next('label').css('border-style', border_style);
          }
        });

        // Switch border color to background color.
        once('blb_border','input.bs-field-border-color-' + directions[i], context).forEach(function (value,i) {
          var border_color = '';
          if ($(value).val() !='_none' && typeof $(value).next('label').css('border-color') != 'undefined') {
            border_color = $(value).next('label').css('border-' + directions[i] + '-color');
            $(value).next('label').attr('style', 'background-color: ' + border_color + ' !important; border-color: white !important;');
          }
        });

      }
    }
  };

})(jQuery, Drupal, once);
