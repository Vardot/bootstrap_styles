<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('text_colors', $form_state->getValue('text_colors'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {

    $form['text_color'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('text_colors'),
      '#title' => $this->t('Text color'),
      '#default_value' => $storage['text_color']['class'] ?? NULL,
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-text-color', 'bs_input-circles'],
      ],
      '#states' => [
        'visible' => [
          ':input.bs_text--type' => ['value' => 'color'],
        ],
      ],
    ];

    // Attach the Layout Builder form style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.text_color.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'text_color' => [
        'class' => $group_elements['text_color'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    // Backwards compatibility for layouts created on the 1.x version.
    $text_type = $storage['text']['text_type'] ?? 'color';

    if ($text_type == 'color') {
      // Assign the style to element or its theme wrapper if exist.
      if ($theme_wrapper && isset($build['#theme_wrappers'][$theme_wrapper])) {
        $build['#theme_wrappers'][$theme_wrapper]['#attributes']['class'][] = $storage['text_color']['class'];
      }
      else {
        $build['#attributes']['class'][] = $storage['text_color']['class'];
      }
    }

    // Attach bs-classes to the build.
    $build['#attached']['library'][] = 'bootstrap_styles/plugin.text_color.build';

    return $build;
  }

}
