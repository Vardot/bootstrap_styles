/**
 * @file
 * Behaviors border plugin group.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Border preview box.
  Drupal.behaviors.borderPreview = {
    attach: function (context) {
      var border_width = drupalSettings.bootstrap_styles.border.border_width;
      var rounded_corners = drupalSettings.bootstrap_styles.border.rounded_corners;
      var directions = ['left', 'top', 'right', 'bottom'];
      var corners = ['top_left', 'top_right', 'bottom_left', 'bottom_right'];

      // Refresh preview Classes.
      function refreshPreviewClasses() {
        var border_classes = '';

        // Border style.
        $('input.bs-field-border-style').each(function() {
          if ($(this).is(':checked') && $(this).val() != '_none') { 
            border_classes += $(this).val() + ' ';
          }
        });

        // Border width.
        var border_width_val = $('input.bs-field-border-width').val();
        var border_width_class = border_width.border_width[border_width_val];
        if (border_width_class != '_none') {
          border_classes += border_width_class + ' ';
        }

        // Border color.
        $('input.bs-field-border-color').each(function() {
          if ($(this).is(':checked') && $(this).val() != '_none') { 
            border_classes += $(this).val() + ' ';
          }
        });

        // Loop through the directions.
        for (var i = 0; i < directions.length; i++) {
          // Border style.
          $('input.bs-field-border-' + directions[i] + '-style').each(function() {
            if ($(this).is(':checked') && $(this).val() != '_none') { 
              border_classes += $(this).val() + ' ';
            }
          });

          // Border width
          border_width_val = $('input.bs-field-border-' + directions[i] + '-width').val();
          if (border_width_val) {
            border_width_class = border_width['border_' + directions[i] + '_width'][border_width_val];
            if (border_width_class != '_none') {
              border_classes += border_width_class + ' ';
            }
          }

          // Border color.
          $('input.bs-field-border-' + directions[i] + '-color').each(function() {
            if ($(this).is(':checked') && $(this).val() != '_none') { 
              border_classes += $(this).val() + ' ';
            }
          });
        }

        // Rounded corners
        var rounded_corners_val = $('input.bs-field-rounded-corners').val();
        var rounded_corners_class = rounded_corners.rounded_corners[rounded_corners_val];
        if (rounded_corners_class != '_none') {
          border_classes += rounded_corners_class + ' ';
        }

        // Loop through the corners.
        for (var i = 0; i < corners.length; i++) {
          rounded_corners_val = $('input.bs-field-rounded-corner-' + corners[i]).val();
          if (rounded_corners_val) {
            rounded_corners_class = rounded_corners['rounded_corner_' + corners[i]][rounded_corners_val];
            if (rounded_corners_class != '_none') {
              border_classes += rounded_corners_class + ' ';
            }
          }
        }

        // Remove all classes.
        $('#bs-border-preview').removeClass();
        // Then add the round corner classes.
        $('#bs-border-preview').addClass(border_classes);
      }

      refreshPreviewClasses();

      // Refresh the border classes on change.
      $('input.bs-field-border-style, input.bs-field-border-left-style, input.bs-field-border-top-style, input.bs-field-border-right-style, input.bs-field-border-bottom-style, input.bs-field-border-width, input.bs-field-border-left-width, input.bs-field-border-top-width, input.bs-field-border-right-width, input.bs-field-border-bottom-width, input.bs-field-border-color, input.bs-field-border-left-color, input.bs-field-border-top-color, input.bs-field-border-right-color, input.bs-field-border-bottom-color, input.bs-field-rounded-corners, input.bs-field-rounded-corner-top_left, input.bs-field-rounded-corner-top_right, input.bs-field-rounded-corner-bottom_left, input.bs-field-rounded-corner-bottom_right', context).on('change', function() {
        refreshPreviewClasses();
      });
  
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
