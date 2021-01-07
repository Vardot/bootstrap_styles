<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\StylesGroup;

use Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Background.
 *
 * @package Drupal\bootstrap_styles\Plugin\StylesGroup
 *
 * @StylesGroup(
 *   id = "background",
 *   title = @Translation("Background"),
 *   weight = 1,
 *   icon = "bootstrap_styles/images/plugins/background-icon.svg"
 * )
 */
class Background extends StylesGroupPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['background'] = [
      '#type' => 'details',
      '#title' => $this->t('Background'),
      '#open' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {
    // Responsive.
    $form['background_responsive'] = [
      '#type' => 'radios',
      '#options' => [
        'all' => $this->t('All'),
        'desktop' => $this->t('Desktop'),
        'laptop' => $this->t('Laptop'),
        'tablet' => $this->t('Tablet'),
        'mobile' => $this->t('Mobile'),
      ],
      '#title' => $this->t('Responsive'),
      '#title_display' => 'invisible',
      '#default_value' => 'all',
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['bs_col--full', 'bs_responsive'],
      ],
    ];
    // End responsive.

    $form['background_type'] = [
      '#type' => 'radios',
      '#options' => [],
      '#title' => $this->t('Background type'),
      '#title_display' => 'invisible',
      '#default_value' => NULL,
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['bs_col--full', 'bs_background--type'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'background' => [
        'background_type' => $group_elements['background_type'],
      ],
    ];
  }

}
