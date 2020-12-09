/**
 * @file
 * Behaviors Background Color plugin layout builder form scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Background color.
  Drupal.behaviors.backgroundColorLayoutBuilderForm = {
    attach: function (context) {

      $(".fieldgroup.field-background-color input:radio", context).once('blb_bg-color').each(function () {
        var bgColor = $(this).next('label').css('background-color');
        var bgColorHex = rgb2hex(bgColor);
        var bgColorContrast = getContrast(bgColorHex);

        // Add bg class to our label so we get a little preview, then set our yiq() contrast class for selected state.
        $(this).next('label').addClass($(this).val()).addClass('bs_yiq-' + bgColorContrast);
      });

      $(".fieldgroup.field-background-color .fieldset-wrapper label", context).on('click', function () {
        $(this).parents('.fieldset-wrapper').find('label').removeClass('active');
        $(this).parents('.fieldset-wrapper').addClass('style-selected').find('input').prop("checked", false);
        $(this).parent().find('input').prop('checked', true);

        if($(this).hasClass('_none')) {
          $(this).parents('.fieldset-wrapper').removeClass('style-selected');
        }
      });

      // Custom solution for bootstrap 3 & Bario drupal theme issues.
      $(".fieldgroup.field-background-color .fieldset-wrapper input:radio", context).each(function () {
        $(this).closest('.radio').find('label').addClass($(this).val());
        var checked = $(this).prop("checked");
        if (typeof checked !== typeof undefined && checked !== false) {
          $(this).closest('.radio').find('label').addClass('active');
        }
      });
    }
  };

  //Function to convert hex format to a rgb color
  function rgb2hex(orig){
    var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
    return (rgb && rgb.length === 4) ? "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
  }

  /*!
   * Get the contrasting color for any hex color
   * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
   * Derived from work by Brian Suda, https://24ways.org/2010/calculating-color-contrast/
   * @param  {String} A hexcolor value
   * @return {String} The contrasting color (black or white)
   */
  var getContrast = function (hexcolor){
    // If a leading # is provided, remove it
    if (hexcolor.slice(0, 1) === '#') {
      hexcolor = hexcolor.slice(1);
    }

    // If a three-character hexcode, make six-character
    if (hexcolor.length === 3) {
      hexcolor = hexcolor.split('').map(function (hex) {
        return hex + hex;
      }).join('');
    }

    // Convert to RGB value
    var r = parseInt(hexcolor.substr(0,2),16);
    var g = parseInt(hexcolor.substr(2,2),16);
    var b = parseInt(hexcolor.substr(4,2),16);

    // Get YIQ ratio
    var yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

    // Check contrast
    return (yiq >= 128) ? 'dark' : 'light';
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
