<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\StylesGroup;

use Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Spacing.
 *
 * @package Drupal\bootstrap_styles\Plugin\StylesGroup
 *
 * @StylesGroup(
 *   id = "spacing",
 *   title = @Translation("Spacing"),
 *   weight = 2
 * )
 */
class Spacing extends StylesGroupPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['spacing'] = [
      '#type' => 'details',
      '#title' => $this->t('Spacing'),
      '#open' => TRUE,
    ];

    return $form;
  }

}
