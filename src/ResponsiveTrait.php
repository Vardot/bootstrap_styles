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
   *   The array of field names.
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

  /**
   * Build the breakpoints style form elements.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $fields
   *   The array of fields, the style options key and keyed by field name.
   */
  protected function buildBreakpointsStyleFormElements(array &$form, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name => $style_options_key) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        $form[$field_name . '_' . $breakpoint] = $form[$field_name];
        $form[$field_name . '_' . $breakpoint]['#options'] = $this->getStyleOptions($style_options_key . '_' . $breakpoint);

        $form[$field_name . '_' . $breakpoint]['#default_value'] = $storage[$field_name . '_' . $breakpoint]['class'] ?? NULL;
        $form[$field_name . '_' . $breakpoint]['#states']['visible'][':input.bs_responsive']['value'] = $breakpoint;
        // Hide the generic one.
        $form[$field_name]['#states']['visible'][':input.bs_responsive']['value'] = 'all';
      }
    }
  }

  /**
   * Save the breakpoints fields values for style form.
   *
   * @param array $group_elements
   *   The submitted form values array.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param array $fields
   *   The array of field names.
   */
  protected function submitBreakpointsStyleFormElements(array $group_elements, array &$storage, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        $storage[$field_name . '_' . $breakpoint] = [
          'class' => $group_elements[$field_name . '_' . $breakpoint],
        ];
      }
    }
  }

  /**
   * Add the breakpoints classes to the build classes.
   *
   * @param array $classes
   *   An associative array containing build classes.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param array $fields
   *   The array of field names.
   */
  protected function buildBreakpoints(array &$classes, $storage, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        if (isset($storage[$field_name . '_' . $breakpoint]['class'])) {
          $classes[] = $storage[$field_name . '_' . $breakpoint]['class'];
        }
      }
    }
  }

}
