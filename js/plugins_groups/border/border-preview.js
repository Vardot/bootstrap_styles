/**
 * @file
 * Behaviors border plugin group.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Border preview box.
  Drupal.behaviors.borderPreview = {
    attach: function (context) {
      var rounded_corners = drupalSettings.bootstrap_styles.rounded_corners;
      console.log(rounded_corners);
      // Refresh preview Classes.
      function refreshPreviewClasses() {
        var rounded_corners_val = $('input.bs-field-rounded-corners').val();
        var rounded_corner_top_left_val = $('input.bs-field-rounded-corner-top_left').val();
        var rounded_corner_top_right_val = $('input.bs-field-rounded-corner-top_right').val();
        var rounded_corner_bottom_left_val = $('input.bs-field-rounded-corner-bottom_left').val();
        var rounded_corner_bottom_right_val = $('input.bs-field-rounded-corner-bottom_right').val();
        var border_classes = '';
        var rounded_corners_class = rounded_corners.rounded_corners[rounded_corners_val];
        if (rounded_corners_class != '_none') {
          border_classes += rounded_corners_class + ' ';
        }
        var rounded_corner_top_left_class = rounded_corners.rounded_corner_top_left[rounded_corner_top_left_val];
        if (rounded_corner_top_left_class != '_none') {
          border_classes += rounded_corner_top_left_class + ' ';
        }
        var rounded_corner_top_right_class = rounded_corners.rounded_corner_top_right[rounded_corner_top_right_val];
        if (rounded_corner_top_right_class != '_none') {
          border_classes += rounded_corner_top_right_class + ' ';
        }
        var rounded_corner_bottom_left_class = rounded_corners.rounded_corner_bottom_left[rounded_corner_bottom_left_val];
        if (rounded_corner_bottom_left_class != '_none') {
          border_classes += rounded_corner_bottom_left_class + ' ';
        }
        var rounded_corner_bottom_right_class = rounded_corners.rounded_corner_bottom_right[rounded_corner_bottom_right_val];
        if (rounded_corner_bottom_right_class != '_none') {
          border_classes += rounded_corner_bottom_right_class + ' ';
        }

        // Remove all classes.
        $('#bs-border-preview').removeClass();
        // Then add the round corner classes.
        $('#bs-border-preview').addClass(border_classes);
      }

      refreshPreviewClasses();

      // Refresh the round corner classes on change.
      $('input.bs-field-rounded-corners, input.bs-field-rounded-corner-top_left, input.bs-field-rounded-corner-top_right, input.bs-field-rounded-corner-bottom_left, input.bs-field-rounded-corner-bottom_right', context).on('change', function() {
        refreshPreviewClasses();
      });

    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
