/**
 * @file
 * Behaviors spacing plugin group.
 */

(function ($, Drupal, once) {
  // Spacing preview box.
  Drupal.behaviors.spacingPreview = {
    attach(context, settings) {
      const spacing = settings.bootstrap_styles.spacing;
      const breakpoints = settings.bootstrap_styles.breakpoints;

      const padding_box_shadow = $('.spacing-preview .padding-box').css(
        'box-shadow',
      );
      const margin_box_shadow = $('.spacing-preview .margin-box').css(
        'box-shadow',
      );
      const box_shadow = '0 0 0 1.5rem';

      // Padding.
      function calcPadding() {
        const padding_val = $('input.bs-field-padding').val();
        const padding_left_val = $('input.bs-field-padding-left').val();
        const padding_top_val = $('input.bs-field-padding-top').val();
        const padding_right_val = $('input.bs-field-padding-right').val();
        const padding_bottom_val = $('input.bs-field-padding-bottom').val();
        let padding_classes = '';
        const padding_class =
          spacing.padding_classes_options.padding[padding_val];
        if (padding_class != '_none') {
          padding_classes += `${padding_class} `;
        }
        const padding_left_class =
          spacing.padding_classes_options.padding_left[padding_left_val];
        if (padding_left_class != '_none') {
          padding_classes += `${padding_left_class} `;
        }
        const padding_top_class =
          spacing.padding_classes_options.padding_top[padding_top_val];
        if (padding_top_class != '_none') {
          padding_classes += `${padding_top_class} `;
        }
        const padding_right_class =
          spacing.padding_classes_options.padding_right[padding_right_val];
        if (padding_right_class != '_none') {
          padding_classes += `${padding_right_class} `;
        }
        const padding_bottom_class =
          spacing.padding_classes_options.padding_bottom[padding_bottom_val];
        if (padding_bottom_class != '_none') {
          padding_classes += `${padding_bottom_class} `;
        }

        // Responsive.
        padding_classes += getActiveBreakpointPaddingClasses();

        // Remove all classes.
        $('#bs_spacing_preview_calc').removeClass();
        // Then add the padding classes.
        $('#bs_spacing_preview_calc').addClass(padding_classes);

        $('.spacing-preview .padding-left').text(
          parseInt($('#bs_spacing_preview_calc').css('padding-left')),
        );
        $('.spacing-preview .padding-top').text(
          parseInt($('#bs_spacing_preview_calc').css('padding-top')),
        );
        $('.spacing-preview .padding-right').text(
          parseInt($('#bs_spacing_preview_calc').css('padding-right')),
        );
        $('.spacing-preview .padding-bottom').text(
          parseInt($('#bs_spacing_preview_calc').css('padding-bottom')),
        );
      }

      // Responsive.
      function getActiveBreakpointPaddingClasses() {
        var padding_classes = '';
        const active_breakpoint = $(
          '.bs_responsive_spacing input:checked',
        ).val();

        if (typeof active_breakpoint !== 'undefined') {
          let i;
          var padding_classes = '';
          for (i = 0; i < breakpoints.length; i++) {
            if (active_breakpoint == breakpoints[i]) {
              const padding_val = $(
                `input.bs-field-padding-${breakpoints[i]}`,
              ).val();
              const padding_left_val = $(
                `input.bs-field-padding-left-${breakpoints[i]}`,
              ).val();
              const padding_top_val = $(
                `input.bs-field-padding-top-${breakpoints[i]}`,
              ).val();
              const padding_right_val = $(
                `input.bs-field-padding-right-${breakpoints[i]}`,
              ).val();
              const padding_bottom_val = $(
                `input.bs-field-padding-bottom-${breakpoints[i]}`,
              ).val();
              const padding_class =
                spacing.padding_classes_options[`padding_${breakpoints[i]}`][
                  padding_val
                ];
              if (padding_class != '_none') {
                padding_classes += `${padding_class} `;
              }
              const padding_left_class =
                spacing.padding_classes_options[
                  `padding_left_${breakpoints[i]}`
                ][padding_left_val];
              if (padding_left_class != '_none') {
                padding_classes += `${padding_left_class} `;
              }
              const padding_top_class =
                spacing.padding_classes_options[
                  `padding_top_${breakpoints[i]}`
                ][padding_top_val];
              if (padding_top_class != '_none') {
                padding_classes += `${padding_top_class} `;
              }
              const padding_right_class =
                spacing.padding_classes_options[
                  `padding_right_${breakpoints[i]}`
                ][padding_right_val];
              if (padding_right_class != '_none') {
                padding_classes += `${padding_right_class} `;
              }
              const padding_bottom_class =
                spacing.padding_classes_options[
                  `padding_bottom_${breakpoints[i]}`
                ][padding_bottom_val];
              if (padding_bottom_class != '_none') {
                padding_classes += `${padding_bottom_class} `;
              }
            }
          }
        }

        return padding_classes;
      }

      // Margin.
      function calcMargin() {
        const margin_val = $('input.bs-field-margin').val();
        const margin_left_val = $('input.bs-field-margin-left').val();
        const margin_top_val = $('input.bs-field-margin-top').val();
        const margin_right_val = $('input.bs-field-margin-right').val();
        const margin_bottom_val = $('input.bs-field-margin-bottom').val();

        let margin_classes = '';
        const margin_class = spacing.margin_classes_options.margin[margin_val];
        if (margin_class != '_none') {
          margin_classes += `${margin_class} `;
        }
        const margin_left_class =
          spacing.margin_classes_options.margin_left[margin_left_val];
        if (margin_left_class != '_none') {
          margin_classes += `${margin_left_class} `;
        }
        const margin_top_class =
          spacing.margin_classes_options.margin_top[margin_top_val];
        if (margin_top_class != '_none') {
          margin_classes += `${margin_top_class} `;
        }
        const margin_right_class =
          spacing.margin_classes_options.margin_right[margin_right_val];
        if (margin_right_class != '_none') {
          margin_classes += `${margin_right_class} `;
        }
        const margin_bottom_class =
          spacing.margin_classes_options.margin_bottom[margin_bottom_val];
        if (margin_bottom_class != '_none') {
          margin_classes += `${margin_bottom_class} `;
        }

        // Remove all classes.
        $('#bs_spacing_preview_calc').removeClass();
        // Then add the margin classes.
        $('#bs_spacing_preview_calc').addClass(margin_classes);

        $('.spacing-preview .margin-left').text(
          parseInt($('#bs_spacing_preview_calc').css('margin-left')),
        );
        $('.spacing-preview .margin-top').text(
          parseInt($('#bs_spacing_preview_calc').css('margin-top')),
        );
        $('.spacing-preview .margin-right').text(
          parseInt($('#bs_spacing_preview_calc').css('margin-right')),
        );
        $('.spacing-preview .margin-bottom').text(
          parseInt($('#bs_spacing_preview_calc').css('margin-bottom')),
        );
      }

      // Calculate the padding on load.
      calcPadding();
      // Calculate the margin on load.
      calcMargin();

      // Padding Actions
      // Calculate the padding on change.
      $(
        'input[class^="bs-field-padding"], .bs_responsive_spacing input',
        context,
      ).on('change', function () {
        calcPadding();
      });

      // On focus, gray out margin box so we can "focus" on changing padding values.
      $('input[class^="bs-field-padding"]', context).on('focus', function () {
        const panel_bg = $(this)
          .parents('details')
          .find('summary')
          .css('background-color');
        const inactive_box_shadow = `${box_shadow} ${panel_bg}`;
        $('.spacing-preview .margin-box').css(
          'box-shadow',
          inactive_box_shadow,
        );
        $('.spacing-preview .padding-box').addClass('bs-adjusting');
      });

      // On focusout, turn it back to our original color.
      $('input[class^="bs-field-padding"]', context).on(
        'focusout',
        function () {
          $('.spacing-preview .margin-box').css(
            'box-shadow',
            margin_box_shadow,
          );
          $('.spacing-preview .padding-box').removeClass('bs-adjusting');
        },
      );

      // Margin Actions
      // Calculate the margin on change.
      $('input[class^="bs-field-margin"]', context).on('change', function () {
        calcMargin();
      });

      // On focus, gray out margin box so we can "focus" on changing padding values.
      $('input[class^="bs-field-margin"]', context).on('focus', function () {
        const panel_bg = $(this)
          .parents('details')
          .find('summary')
          .css('background-color');
        const inactive_box_shadow = `${box_shadow} ${panel_bg}`;
        $('.spacing-preview .padding-box').css(
          'box-shadow',
          inactive_box_shadow,
        );
        $('.spacing-preview .margin-box').addClass('bs-adjusting');
      });

      // On focusout, turn it back to our original color.
      $('input[class^="bs-field-margin"]', context).on('focusout', function () {
        $('.spacing-preview .padding-box').css(
          'box-shadow',
          padding_box_shadow,
        );
        $('.spacing-preview .margin-box').removeClass('bs-adjusting');
      });
    },
  };
})(jQuery, Drupal, once);
