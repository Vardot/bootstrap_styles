/**
 * @file
 * Behaviors of Bootstrap Layout Builder local video background.
 */

/* global AOS */

(function ($, _, Drupal, drupalSettings) {
  Drupal.behaviors.scrollEffectsInit = {
    attach(context, settings) {
      AOS.init();
    },
  };
})(window.jQuery, window._, window.Drupal, window.drupalSettings);
