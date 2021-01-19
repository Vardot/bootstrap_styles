<?php

namespace Drupal\bootstrap_styles;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Trait for responsive methods.
 */
trait ResponsiveTrait {

  /**
   * The available breakpoint.
   *
   * @return array
   *   Array of breakpoints.
   */
  protected function getBreakpoints() {
    return [
      'desktop' => 'Desktop',
      'laptop' => 'Laptop',
      'tablet' => 'Tablet',
      'mobile' => 'Mobile',
    ];
  }

  /**
   * The available breakpoint.
   *
   * @return array
   *   Array of breakpoints keys.
   */
  protected function getBreakpointsKeys() {
    return array_keys($this->getBreakpoints());
  }

  /**
   * Build the breakpoints configuration form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $fields
   *   The array of fields, each field has its parents and keyed by field name.
   */
  protected function buildBreakpointsConfigurationForm(array &$form, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name => $parents) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpoints() as $breakpoint_key => $breakpoint_value) {
        // Get the original field.
        $field = NestedArray::getValue($form, array_merge($parents, [$field_name]));

        // Change field attributes.
        $field['#title'] .= ' - ' . $breakpoint_value;
        $field['#default_value'] = $this->config()->get($field_name . '_' . $breakpoint_key);

        NestedArray::setValue($form, array_merge($parents, [$field_name . '_' . $breakpoint_key]), $field);
      }
    }
  }

  /**
   * Save the breakpoints fields values to the configuration.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $fields
   *   The array of fields, each field has its parents and keyed by field name.
   */
  protected function submitBreakpointsConfigurationForm(FormStateInterface $form_state, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        $this->config()
          ->set($field_name . '_' . $breakpoint, $form_state->getValue($field_name . '_' . $breakpoint))
          ->save();
      }
    }
  }

}
