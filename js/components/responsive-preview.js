/**
 * @file
 * Behaviors Bootstrap styles responsive preview scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  /**
   * 
   */
  function setTempStore(key, value) {
    $.ajax({
      url: Drupal.url('bootstrap_styles/ajax/temp_store/set'),
      type: 'POST',
      data: {
        key: key,
        value: value
      },
      dataType: 'json'
    });
  }

  /**
   * 
   */
  function loadPreviewer(currentDevice) {
    var devices = {
      'desktop': '1400',
      'laptop': '1199',
      'tablet': '768',
      'mobile': '460',
    };

    // Solve highlight after refresh the section.
    var highlightId = $('form.layout-builder-configure-section').attr('data-layout-builder-target-highlight-id');
    if (highlightId) {
      $('[data-layout-builder-highlight-id="' + highlightId + '"]').addClass(
        'is-layout-builder-highlighted',
      );
    }

    // Close any old dialogs.
    Drupal.dialog($('#bs-responsive-preview-wrapper').get(0)).close();

    if (currentDevice == 'all') {
      return;
    }

    var $clonedDom = $('html').clone();
    
    var $iframeWrapper = $('<div id="bs-responsive-preview-wrapper" class="bs-responsive-preview-wrapper"></div>');

    var $iframe = $('<iframe id="bs-responsive-preview" width="100%" height="100%" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>')
      .on('load', function() {
        var highlightedSection = $(this).contents().find("#bs-preview-highlighted");
        var titleBarHight = $('.bs-responsive-preview-dialog .ui-dialog-titlebar').outerHeight();
        this.contentWindow.scrollTo(0, highlightedSection.offset().top - titleBarHight);
      });

    $iframeWrapper.append($iframe);

    var $previewDialog = $($iframeWrapper).appendTo('body');
    Drupal.dialog($previewDialog, {
      dialogClass: 'bs-responsive-preview-dialog ui-dialog-off-canvas ui-dialog-position-side',
      title: currentDevice + ' preview',
      width: devices[currentDevice],
      height: '600'
    },
    ).showModal();
    
    $('.bs-responsive-preview-dialog').css('left', '60px !important');
    
    // Make bs overlay unique.
    $('.ui-widget-overlay').addClass('bs-ui-widget-overlay');

    // Add Id selector to the highlighted section.
    $clonedDom.find('.is-layout-builder-highlighted').attr('id', 'bs-preview-highlighted');

    var removedClasses = [
      'layout-builder',
      'layout-builder__region',
      'layout-builder-block',
      'is-layout-builder-highlighted',
      'js-off-canvas-dialog-open',
    ];

    for (var i = 0; i < removedClasses.length; i++) {
      $clonedDom.find('.' + removedClasses[i]).removeClass(removedClasses[i]);
    }

    var removedElementsBySelector = [
      '.layout-builder__add-section',
      '.layout-builder__add-block',
      '.layout-builder__link',
      '.layout-builder-form',
      '.ui-dialog',
      '.ui-widget-overlay',
    ];

    for (i = 0; i < removedClasses.length; i++) {
      $clonedDom.find(removedElementsBySelector[i]).remove();
    }

    // Remove style attr with its padding.
    $clonedDom.find('.dialog-off-canvas-main-canvas').removeAttr('style');
    
    // Write the iframe content.
    var $filterdHtml = $clonedDom.html();
    var $iframeDoc = $iframe[0].contentDocument || $iframe[0].contentWindow.document;
    $iframeDoc.write($filterdHtml);
    $iframe.contents().find( '.bs-ui-widget-overlay').remove();
    $iframeDoc.close();
  }

  Drupal.behaviors.bootstrapStylesResponsivePreview = {
    attach: function (context) {
      // Listen to bs_responsive device click.
      $("input.bs_responsive", context).once().on("click", function () {
        console.log('loaded');
        var currentDevice = $(this).val();
        // set it in the temp store.
        setTempStore('active_device', currentDevice);
        loadPreviewer(currentDevice);
      });
    }
  };

  /**
   * Refresh responsive command.
   */
  Drupal.AjaxCommands.prototype.bs_refresh_responsive = function (ajax, response, status) { 
    var currentDevice = response.data.active_device;
    if (currentDevice != 'all') {
      $('input.bs_responsive[value='+ currentDevice +']').click();
    }
  }

  $(window).on({
    'dialog:beforecreate': function dialogBeforecreate(event, dialog, $element) {
      if ($element.is('#drupal-off-canvas')) {
        // Reset the active device to all.
        setTempStore('active_device', 'all');
      }
    },
    'dialog:beforeclose': function dialogBeforeclose(event, dialog, $element) {
      if ($element.is('#drupal-off-canvas')) {
        // Reset the active device to all.
        setTempStore('active_device', 'all');
      }
    }
  });

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
