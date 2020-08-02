<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BackgroundColor.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "background_color",
 *   title = @Translation("Background Color"),
 *   group_id = "background",
 *   weight = 1
 * )
 */
class BackgroundColor extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->config();

    $form['background'] = [
      '#type' => 'details',
      '#title' => $this->t('Background'),
      '#open' => TRUE,
    ];

    $form['background']['background_colors'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('background_colors'),
      '#title' => $this->t('Background colors (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the background.</p>'),
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
      ->set('background_colors', $form_state->getValue('background_colors'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    $form['background_color'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('background_colors'),
      '#title' => $this->t('Background color'),
      '#default_value' => $storage['background_color']['class'],
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-background-color'],
      ],
    ];

    // Attach the Layout Builder from style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.background_color.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'background_color' => [
        'class' => $group_elements['background_color'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    // Assign the style to element or its theme wrapper if exist.
    if ($theme_wrapper && isset($build['#theme_wrappers'][$theme_wrapper])) {
      $build['#theme_wrappers'][$theme_wrapper]['#attributes']['class'][] = $storage['class'];
    }
    else {
      $build['#attributes']['class'][] = $storage['class'];
    }
    return $build;
  }

}
