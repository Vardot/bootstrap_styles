/**
 * @file
 * Behaviors of Bootstrap Layout Builder local video background.
 */

(function ($, _, Drupal, drupalSettings) {
  Drupal.behaviors.backgroundMediaBuildLocalVideoBG = {
    attach(context, settings) {
      // Set the height of the background video.
      $('.background-local-video').each(function () {
        $(this).height(function () {
          return $(this).find('.video-content > div').outerHeight();
        });
      });
    },
  };
})(window.jQuery, window._, window.Drupal, window.drupalSettings);
