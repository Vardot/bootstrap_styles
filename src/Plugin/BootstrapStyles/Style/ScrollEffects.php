<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Effect.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "scroll_effects",
 *   title = @Translation("Scroll Effects"),
 *   group_id = "animation",
 *   weight = 1
 * )
 */
class ScrollEffects extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->config();

    $form['animation'] = [
      '#type' => 'details',
      '#title' => $this->t('Animation'),
      '#open' => TRUE,
    ];

    $form['animation']['scroll_effects'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('scroll_effects'),
      '#title' => $this->t('Scroll Effects (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the effect.</p>'),
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
      ->set('scroll_effects', $form_state->getValue('scroll_effects'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    $form['scroll_effects'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('scroll_effects'),
      '#title' => $this->t('Scroll Effects'),
      '#default_value' => $storage['scroll_effects']['class'],
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-scroll-effects'],
      ],
    ];

    // Attach the Layout Builder from style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.scroll_effects.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'scroll_effects' => [
        'class' => $group_elements['scroll_effects'],
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
    // $build['#attached']['library'][] = 'bootstrap_styles/plugin.scroll_effects.build';
    return $build;
  }

}
