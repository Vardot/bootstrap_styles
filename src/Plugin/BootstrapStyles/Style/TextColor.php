<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bootstrap_styles\ResponsiveTrait;

/**
 * Class TextColor.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "text_color",
 *   title = @Translation("Text Color"),
 *   group_id = "typography",
 *   weight = 1
 * )
 */
class TextColor extends StylePluginBase {
  use ResponsiveTrait;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->config();

    $form['typography']['text_colors'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('text_colors'),
      '#title' => $this->t('Text colors (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the text.</p>'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    // Responsive.
    $fields = [
      'text_colors' => ['typography'],
    ];
    $this->buildBreakpointsConfigurationForm($form, $fields);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('text_colors', $form_state->getValue('text_colors'))
      ->save();

    // Responsive.
    $fields = [
      'text_colors',
    ];

    $this->submitBreakpointsConfigurationForm($form_state, $fields);
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {

    $form['text_color'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('text_colors'),
      '#title' => $this->t('Text Color'),
      '#default_value' => $storage['text_color']['class'] ?? NULL,
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-text-color', 'bs_input-circles', 'with-selected-gradient'],
      ],
    ];

    // Responsive.
    $this->createBreakpointsStyleFormFields($form, 'text_color', 'typography', $storage, 'text_colors');

    // Attach the Layout Builder form style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.text_color.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    $storage = [
      'text_color' => [
        'class' => $group_elements['text_color'],
      ],
    ];

    // Responsive.
    $fields = [
      'text_color',
    ];

    $this->saveBreakpointsStyleFormFields($group_elements, $storage, $fields);

    return $storage;
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    $classes = [];
    if (isset($storage['text_color']['class'])) {
      $classes[] = $storage['text_color']['class'];
    }

    // Responsive.
    $fields = [
      'text_color',
    ];
    $this->buildBreakpoints($classes, $storage, $fields);

    // Add the classes to the build.
    $build = $this->addClassesToBuild($build, $classes, $theme_wrapper);

    // Attach bs-classes to the build.
    $build['#attached']['library'][] = 'bootstrap_styles/plugin.text_color.build';

    return $build;
  }

}
