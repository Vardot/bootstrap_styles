<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Border.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "border",
 *   title = @Translation("Border"),
 *   group_id = "border",
 *   weight = 1
 * )
 */
class Border extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->config();
    $directions = [
      'left',
      'top',
      'right',
      'bottom',
    ];

    // Border style.
    $form['border']['style_description'] = [
      '#type' => 'item',
      '#title' => $this->t('Border style'),
      '#markup' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the border style.</p>'),
    ];

    $form['border']['style_group'] = [
      '#type' => 'container',
      '#title' => $this->t('Border style group.'),
      '#title_display' => 'invisible',
      '#tree' => FALSE,
      '#attributes' => [
        'class' => [
          'bs-admin-d-lg-flex',
          'bs-admin-group-form-item-lg-ml',
        ],
      ],
    ];

    $form['border']['style_group']['border_style'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('border_style'),
      '#title' => $this->t('Border style (classes)'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    for ($i = 0; $i < 4; $i++) {
      $form['border']['style_group']['border_' . $directions[$i] . '_style'] = [
        '#type' => 'textarea',
        '#default_value' => $config->get('border_' . $directions[$i] . '_style'),
        '#title' => $this->t('Border @direction style (classes)', ['@direction' => $directions[$i]]),
        '#cols' => 60,
        '#rows' => 5,
      ];
    }

    // Border width.
    $form['border']['width_description'] = [
      '#type' => 'item',
      '#title' => $this->t('Border width'),
      '#markup' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the border width.</p>'),
    ];

    $form['border']['width_group'] = [
      '#type' => 'container',
      '#title' => $this->t('Border width group.'),
      '#title_display' => 'invisible',
      '#tree' => FALSE,
      '#attributes' => [
        'class' => [
          'bs-admin-d-lg-flex',
          'bs-admin-group-form-item-lg-ml',
        ],
      ],
    ];

    $form['border']['width_group']['border_width'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('border_width'),
      '#title' => $this->t('Border width (classes)'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    for ($i = 0; $i < 4; $i++) {
      $form['border']['width_group']['border_' . $directions[$i] . '_width'] = [
        '#type' => 'textarea',
        '#default_value' => $config->get('border_' . $directions[$i] . '_width'),
        '#title' => $this->t('Border @direction width (classes)', ['@direction' => $directions[$i]]),
        '#cols' => 60,
        '#rows' => 5,
      ];
    }

    // Border colors.
    $form['border']['color_description'] = [
      '#type' => 'item',
      '#title' => $this->t('Border color'),
      '#markup' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the border color.</p>'),
    ];

    $form['border']['color_group'] = [
      '#type' => 'container',
      '#title' => $this->t('Border color group.'),
      '#title_display' => 'invisible',
      '#tree' => FALSE,
      '#attributes' => [
        'class' => [
          'bs-admin-d-lg-flex',
          'bs-admin-group-form-item-lg-ml',
        ],
      ],
    ];

    $form['border']['color_group']['border_color'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('border_color'),
      '#title' => $this->t('Border colors (classes)'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    for ($i = 0; $i < 4; $i++) {
      $form['border']['color_group']['border_' . $directions[$i] . '_color'] = [
        '#type' => 'textarea',
        '#default_value' => $config->get('border_' . $directions[$i] . '_color'),
        '#title' => $this->t('Border @direction colors (classes)', ['@direction' => $directions[$i]]),
        '#cols' => 60,
        '#rows' => 5,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('border_style', $form_state->getValue('border_style'))
      ->set('border_left_style', $form_state->getValue('border_left_style'))
      ->set('border_top_style', $form_state->getValue('border_top_style'))
      ->set('border_right_style', $form_state->getValue('border_right_style'))
      ->set('border_bottom_style', $form_state->getValue('border_bottom_style'))
      ->set('border_width', $form_state->getValue('border_width'))
      ->set('border_left_width', $form_state->getValue('border_left_width'))
      ->set('border_top_width', $form_state->getValue('border_top_width'))
      ->set('border_right_width', $form_state->getValue('border_right_width'))
      ->set('border_bottom_width', $form_state->getValue('border_bottom_width'))
      ->set('border_color', $form_state->getValue('border_color'))
      ->set('border_left_color', $form_state->getValue('border_left_color'))
      ->set('border_top_color', $form_state->getValue('border_top_color'))
      ->set('border_right_color', $form_state->getValue('border_right_color'))
      ->set('border_bottom_color', $form_state->getValue('border_bottom_color'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    $directions = [
      'left',
      'top',
      'right',
      'bottom',
    ];

    // This only for frontend no storage needed for this field.
    $form['border_type'] = [
      '#type' => 'radios',
      '#options' => [
        'border' => $this->t('Border'),
        'border_left' => $this->t('Left'),
        'border_top' => $this->t('Top'),
        'border_right' => $this->t('Right'),
        'border_bottom' => $this->t('Bottom'),
      ],
      '#title' => $this->t('Border type'),
      '#title_display' => 'invisible',
      '#default_value' => 'border',
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['bs_col--full', 'bs_radio-tabs', 'bs_border--type'],
      ],
    ];

    $form['border_style'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('border_style'),
      '#title' => $this->t('Border style'),
      '#default_value' => $storage['border']['border_style']['class'],
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-border-style'],
      ],
      '#states' => [
        'visible' => [
          ':input.bs_border--type' => ['value' => 'border'],
        ],
      ],
    ];

    $form['border_width'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('border_width'),
      '#title' => $this->t('Border width'),
      '#default_value' => $storage['border']['border_width']['class'],
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-border-width'],
      ],
      '#states' => [
        'visible' => [
          ':input.bs_border--type' => ['value' => 'border'],
        ],
      ],
    ];

    $form['border_color'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('border_color'),
      '#title' => $this->t('Border color'),
      '#default_value' => $storage['border']['border_color']['class'],
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-border-color'],
      ],
      '#states' => [
        'visible' => [
          ':input.bs_border--type' => ['value' => 'border'],
        ],
      ],
    ];

    for ($i = 0; $i < 4; $i++) {
      $form['border_' . $directions[$i] . '_style'] = [
        '#type' => 'radios',
        '#options' => $this->getStyleOptions('border_' . $directions[$i] . '_style'),
        '#title' => $this->t('Border style'),
        '#default_value' => $storage['border']['border_' . $directions[$i] . '_style']['class'],
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-border-style-' . $directions[$i]],
        ],
        '#states' => [
          'visible' => [
            ':input.bs_border--type' => ['value' => 'border_' . $directions[$i]],
          ],
        ],
      ];

      $form['border_' . $directions[$i] . '_width'] = [
        '#type' => 'radios',
        '#options' => $this->getStyleOptions('border_' . $directions[$i] . '_width'),
        '#title' => $this->t('Border width'),
        '#default_value' => $storage['border']['border_' . $directions[$i] . '_width']['class'],
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-border-width-' . $directions[$i]],
        ],
        '#states' => [
          'visible' => [
            ':input.bs_border--type' => ['value' => 'border_' . $directions[$i]],
          ],
        ],
      ];

      $form['border_' . $directions[$i] . '_color'] = [
        '#type' => 'radios',
        '#options' => $this->getStyleOptions('border_' . $directions[$i] . '_color'),
        '#title' => $this->t('Border color'),
        '#default_value' => $storage['border']['border_' . $directions[$i] . '_color']['class'],
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-border-color-' . $directions[$i]],
        ],
        '#states' => [
          'visible' => [
            ':input.bs_border--type' => ['value' => 'border_' . $directions[$i]],
          ],
        ],
      ];
    }

    // Attach the Layout Builder form style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.border.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    $directions = [
      'left',
      'top',
      'right',
      'bottom',
    ];

    $schema = [
      'border' => [
        'border_style' => [
          'class' => $group_elements['border_style'],
        ],
        'border_width' => [
          'class' => $group_elements['border_width'],
        ],
        'border_color' => [
          'class' => $group_elements['border_color'],
        ],
      ],
    ];

    for ($i = 0; $i < 4; $i++) {
      $schema['border']['border_' . $directions[$i] . '_style']['class'] = $group_elements['border_' . $directions[$i] . '_style'];
      $schema['border']['border_' . $directions[$i] . '_width']['class'] = $group_elements['border_' . $directions[$i] . '_width'];
      $schema['border']['border_' . $directions[$i] . '_color']['class'] = $group_elements['border_' . $directions[$i] . '_color'];
    }

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    $classes = [];
    $classes[] = $storage['border']['border_style']['class'];
    $classes[] = $storage['border']['border_width']['class'];
    $classes[] = $storage['border']['border_color']['class'];

    $directions = [
      'left',
      'top',
      'right',
      'bottom',
    ];

    for ($i = 0; $i < 4; $i++) {
      $classes[] = $storage['border']['border_' . $directions[$i] . '_style']['class'];
      $classes[] = $storage['border']['border_' . $directions[$i] . '_width']['class'];
      $classes[] = $storage['border']['border_' . $directions[$i] . '_color']['class'];
    }

    // Assign the style to element or its theme wrapper if exist.
    if ($theme_wrapper && isset($build['#theme_wrappers'][$theme_wrapper])) {
      $build['#theme_wrappers'][$theme_wrapper]['#attributes']['class'] = array_merge($build['#theme_wrappers'][$theme_wrapper]['#attributes']['class'], $classes);
    }
    else {
      $build['#attributes']['class'] = array_merge($build['#attributes']['class'], $classes);
    }

    // Attach bs-classes to the build.
    $build['#attached']['library'][] = 'bootstrap_styles/plugin.border.build';

    return $build;
  }

}
