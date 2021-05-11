/**
 * @file
 * Behaviors Bootstrap styles responsive preview scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  // Do all the things to kill our previewer.
  $(document).on("keyup", function (e) {
    if (e.key == "Escape") {
      if ($('.bs-responsive-preview-wrapper').length) {
        destroyPreviewer();
      }
    }
  });

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
   * Load up our responsive plugin previewer.
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
    destroyPreviewer();

    if (currentDevice == 'all') {
      return;
    }

    var $clonedDom = $('html').clone();

    var $iframeWrapper = $('' +
      '<div id="bs-responsive-preview-wrapper" class="bs-responsive-preview-wrapper">' +
      '<div class="bs-responsive-preview-scroll-track">' +
      '<div class="bs-responsive-preview-scroll-pane">' +
      '<div class="bs-responsive-preview-container">' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>');

    var $iframe = $('<iframe id="bs-responsive-preview" width="100%" height="100%" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>')
      .on('load', function () {
        var highlightedSection = $(this).contents().find("#bs-preview-highlighted");
        this.contentWindow.scrollTo(0, highlightedSection.offset().top);
      });

    // append the iframe to our deepest div
    $iframeWrapper.find('.bs-responsive-preview-container').append($iframe);

    // append the whole previewer to the window
    $($iframeWrapper).appendTo('body');

    // set the device width
    $iframeWrapper.find('.bs-responsive-preview-container').css({
      'width': devices[currentDevice],
    });

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
    $iframe.contents().find('.bs-ui-widget-overlay').remove();
    $iframeDoc.close();

    setPreviewerSize();
  }

  /**
   * Close our responsive plugin previewer.
   */
  function destroyPreviewer() {
    $('#bs-responsive-preview-wrapper').remove();
  }

  /**
   * Sets the viewport width and height dimensions on the envModel.
   */
  function setPreviewerSize() {
    if($('#drupal-off-canvas').length > 0) {
      var viewportWidth = document.documentElement.clientWidth;
      var offcanvasWidth = $('#drupal-off-canvas').css('width');
      var toolbarHeight = $('.ui-dialog-off-canvas').css('top');

      $('.bs-responsive-preview-container').css('max-width', (viewportWidth - offcanvasWidth));
      $('.bs-responsive-preview-scroll-pane').css({
        'padding-right': offcanvasWidth,
        'padding-top': toolbarHeight
      });
    }
  }

  // Init all of our responsive previewer stuff.
  Drupal.behaviors.bootstrapStylesResponsivePreview = {
    attach: function (context) {

      // Listen to bs_responsive device click.
      $("input.bs_responsive", context).once().on("click", function () {
        var currentDevice = $(this).val();
        // set it in the temp store.
        setTempStore('active_device', currentDevice);
        loadPreviewer(currentDevice);
      });

      // Close our previewer if the offcanvas menu is closed.
      $(document, context).once().on('click', '.ui-dialog-titlebar-close', function () {
        destroyPreviewer();
      });

      // Our resize handler.
      $(window).on('load resize', Drupal.debounce(function () {
        setPreviewerSize();
      }, 300));
    }
  };


  /**
   * Refresh responsive command.
   */
  Drupal.AjaxCommands.prototype.bs_refresh_responsive = function (ajax, response, status) {
    var currentDevice = response.data.active_device;
    if (currentDevice != 'all') {
      $('input.bs_responsive[value=' + currentDevice + ']').click();
    }
  };

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

  Drupal.behaviors.bootstrapStylesResponsiveTooltip = {
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
