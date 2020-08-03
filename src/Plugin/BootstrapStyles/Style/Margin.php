<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Margin.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "margin",
 *   title = @Translation("Margin"),
 *   group_id = "spacing",
 *   weight = 2
 * )
 */
class Margin extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->config();

    $form['spacing'] = [
      '#type' => 'details',
      '#title' => $this->t('Spacing'),
      '#open' => TRUE,
    ];

    $form['spacing']['margin'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('margin'),
      '#title' => $this->t('Margin (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the margin.</p>'),
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
      ->set('margin', $form_state->getValue('margin'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    $form['margin'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('margin'),
      '#title' => $this->t('Margin'),
      '#default_value' => $storage['margin']['class'],
      '#validated' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'margin' => [
        'class' => $group_elements['margin'],
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
