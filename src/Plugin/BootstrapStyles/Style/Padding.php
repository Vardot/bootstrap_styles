<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Padding.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "padding",
 *   title = @Translation("Padding"),
 *   group_id = "spacing",
 *   weight = 1
 * )
 */
class Padding extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->config();

    $form['spacing']['padding'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('padding'),
      '#title' => $this->t('Padding (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the padding.</p>'),
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
      ->set('padding', $form_state->getValue('padding'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    $form['padding'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('padding'),
      '#title' => $this->t('Padding'),
      '#default_value' => $storage['padding']['class'],
      '#validated' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'padding' => [
        'class' => $group_elements['padding'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    // Assign the style to element or its theme wrapper if exist.
    if ($theme_wrapper && isset($build['#theme_wrappers'][$theme_wrapper])) {
      $build['#theme_wrappers'][$theme_wrapper]['#attributes']['class'][] = $storage['padding']['class'];
    }
    else {
      $build['#attributes']['class'][] = $storage['padding']['class'];
    }
    return $build;
  }

}
