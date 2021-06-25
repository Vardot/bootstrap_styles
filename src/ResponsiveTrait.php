<?php

namespace Drupal\bootstrap_styles;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Trait for responsive methods.
 */
trait ResponsiveTrait {

  use HelperTrait;

  /**
   * The available breakpoint.
   *
   * @return array
   *   Array of breakpoints.
   */
  protected function getBreakpoints() {
    return [
      'desktop' => $this->t('Desktop'),
      'laptop' => $this->t('Laptop'),
      'tablet' => $this->t('Tablet'),
      'mobile' => $this->t('Mobile'),
    ];
  }

  /**
   * Build the responsive previewer form elements.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   */
  protected function buildResponsivePreviewer(array &$form) {
    $icon_path = drupal_get_path('module', 'bootstrap_styles') . '/images/';

    $form['bs_responsive'] = [
      '#type' => 'radios',
      '#options' => [
        'all' => $this->getSvgIconMarkup($icon_path . 'responsive/device-all.svg'),
      ],
      '#title' => $this->t('Responsive'),
      '#title_display' => 'invisible',
      '#default_value' => 'all',
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['bs_col--full', 'bs_responsive', 'bs_responsive_bar'],
      ],
      '#disable_live_preview' => TRUE,
    ];

    // Loop through the breakpoints.
    foreach ($this->getBreakpoints() as $breakpoint_key => $breakpoint_value) {
      $form['bs_responsive']['#options'][$breakpoint_key] = $this->getSvgIconMarkup($icon_path . 'responsive/device-' . $breakpoint_key . '.svg');
    }

    // Attach admin form style.
    $form['#attached']['library'][] = 'bootstrap_styles/bs_responsive';
  }

  /**
   * Build the breakpoints style form elements.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param string $group_name
   *   The name of group that we like to add responsive to its plugins.
   */
  protected function buildBreakpointsFields(array &$form, $group_name) {
    $icon_path = drupal_get_path('module', 'bootstrap_styles') . '/images/';

    $form['bs_responsive_' . $group_name] = [
      '#type' => 'radios',
      '#options' => [
        'all' => $this->getSvgIconMarkup($icon_path . 'responsive/device-all.svg'),
      ],
      '#title' => $this->t('Responsive'),
      '#title_display' => 'invisible',
      '#default_value' => 'all',
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['bs_col--full', 'bs_responsive', 'bs_responsive_' . $group_name],
      ],
      '#disable_live_preview' => TRUE,
    ];

    // Loop through the breakpoints.
    foreach ($this->getBreakpoints() as $breakpoint_key => $breakpoint_value) {
      $form['bs_responsive_' . $group_name]['#options'][$breakpoint_key] = $this->getSvgIconMarkup($icon_path . 'responsive/device-' . $breakpoint_key . '.svg');
    }
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
   * Get breakpoint title by key.
   *
   * @return string
   *   breakpoint title.
   */
  protected function getBreakpointTitle($breakpoint_key) {
    return $this->getBreakpoints()[$breakpoint_key];
  }

  /**
   * Build the breakpoints configuration form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $fields
   *   The array of fields, each field has its parents and keyed by field name.
   */
  protected function createBreakpointFormField(array &$form, string $breakpoint_key, string $field_name, array $original_parents, array $new_parents = []) {
    // Loop through the breakpoints.
    // foreach ($this->getBreakpoints() as $breakpoint_key => $breakpoint_value) {
    // Get the original field.
    $field = NestedArray::getValue($form, array_merge($original_parents, [$field_name]));

    // Change field attributes.
    $field['#title'] .= ' - ' . $this->getBreakpointTitle($breakpoint_key);
    $field['#default_value'] = $this->config()->get($field_name . '_' . $breakpoint_key);
    $target_parents = $original_parents;
    if ($new_parents) {
      $target_parents = $new_parents;
    }
    NestedArray::setValue($form, array_merge($target_parents, [$field_name . '_' . $breakpoint_key]), $field);
    // }
  }

  /**
   * TODO: Remove and replace this function.
   *
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
   * Create breakpoint field for a given field.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param string $field_name
   *   The field name.
   * @param string $breakpoint_key
   *   The breakpoint key.
   * @param string $group_id
   *   The plugin group id.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param string $storage_key
   *   The storage key of the field in the storage array if
   *   it's different from the field name.
   */
  protected function createBreakpointStyleFormField(array &$form, string $field_name, string $breakpoint_key, string $group_id, array $storage, string $storage_key = NULL) {
    $form[$field_name . '_' . $breakpoint_key] = $form[$field_name];
    $form[$field_name . '_' . $breakpoint_key]['#options'] = $this->getStyleOptions($storage_key . '_' . $breakpoint_key);

    $form[$field_name . '_' . $breakpoint_key]['#default_value'] = $storage[$field_name . '_' . $breakpoint_key]['class'] ?? NULL;
    $form[$field_name . '_' . $breakpoint_key]['#validated'] = TRUE;
    $form[$field_name . '_' . $breakpoint_key]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = $breakpoint_key;
    // Hide the generic one.
    $form[$field_name]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = 'all';
  }

  /**
   * Create breakpoints fields for a given field.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param string $field_name
   *   The field name.
   * @param string $group_id
   *   The plugin group id.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param string $storage_key
   *   The storage key of the field in the storage array if
   *   it's different from the field name.
   */
  protected function createBreakpointsStyleFormFields(array &$form, string $field_name, string $group_id, array $storage, string $storage_key = NULL) {
    foreach ($this->getBreakpointsKeys() as $breakpoint_key) {
      $this->createBreakpointStyleFormField($form, $field_name, $breakpoint_key, $group_id, $storage, $storage_key);
    }
  }

  /**
   * Create breakpoint field for class index based field like padding.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param string $field_name
   *   The field name.
   * @param string $breakpoint_key
   *   The breakpoint key.
   * @param string $group_id
   *   The plugin group id.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param string $storage_key
   *   The storage key of the field in the storage array if
   *   it's different from the field name.
   */
  protected function createBreakpointStyleFormClassIndexBasedField(array &$form, string $field_name, string $breakpoint_key, string $group_id, array $storage, string $storage_key = NULL) {
    $breakpoint_default_value = 0;
    if (isset($storage[$field_name . '_' . $breakpoint_key]['class'])) {
      $breakpoint_default_value = $this->getStyleOptionIndexByClass($field_name . '_' . $breakpoint_key, $storage[$field_name . '_' . $breakpoint_key]['class']);
    }

    $form[$field_name . '_' . $breakpoint_key] = $form[$field_name];
    $form[$field_name . '_' . $breakpoint_key]['#options'] = $this->getStyleOptions($field_name . '_' . $breakpoint_key);
    $form[$field_name . '_' . $breakpoint_key]['#max'] = $this->getStyleOptionsCount($field_name . '_' . $breakpoint_key);

    $form[$field_name . '_' . $breakpoint_key]['#default_value'] = $breakpoint_default_value;
    $form[$field_name . '_' . $breakpoint_key]['#validated'] = TRUE;

    // Add the breakpoints to the classes if exists.
    if (isset($form[$field_name . '_' . $breakpoint_key]['#attributes']['class'])) {
      foreach ($form[$field_name . '_' . $breakpoint_key]['#attributes']['class'] as $key => $value) {
        $form[$field_name . '_' . $breakpoint_key]['#attributes']['class'][$key] = $value . '-' . $breakpoint_key;
      }
    }

    $form[$field_name . '_' . $breakpoint_key]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = $breakpoint_key;
    // Hide the generic one.
    $form[$field_name]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = 'all';
  }

  /**
   * Create breakpoints fields for class index based field like padding.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param string $field_name
   *   The field name.
   * @param string $group_id
   *   The plugin group id.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param string $storage_key
   *   The storage key of the field in the storage array if
   *   it's different from the field name.
   */
  protected function createBreakpointsStyleFormClassIndexBasedFields(array &$form, string $field_name, string $group_id, array $storage, string $storage_key = NULL) {
    foreach ($this->getBreakpointsKeys() as $breakpoint_key) {
      $this->createBreakpointStyleFormClassIndexBasedField($form, $field_name, $breakpoint_key, $group_id, $storage, $storage_key);
    }
  }

  /**
   * TODO: RENAME Build the breakpoints style form elements.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $fields
   *   The array of fields, the style options key and keyed by field name.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param string $group_id
   *   The plugin group id.
   */
  protected function buildBreakpointsStyleFormElements(array &$form, array $fields, array $storage, string $group_id) {
    // Loop through the fields.
    foreach ($fields as $field_name => $style_options_key) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        $form[$field_name . '_' . $breakpoint] = $form[$field_name];
        $form[$field_name . '_' . $breakpoint]['#options'] = $this->getStyleOptions($style_options_key . '_' . $breakpoint);

        $form[$field_name . '_' . $breakpoint]['#default_value'] = $storage[$field_name . '_' . $breakpoint]['class'] ?? NULL;
        $form[$field_name . '_' . $breakpoint]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = $breakpoint;
        // Hide the generic one.
        $form[$field_name]['#states']['visible'][':input.bs_responsive_' . $group_id]['value'] = 'all';
      }
    }
  }

  /**
   * Save the breakpoints fields values to the storage.
   *
   * @param array $group_elements
   *   The submitted form values array.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param array $fields
   *   The array of field names.
   */
  protected function saveBreakpointsStyleFormFields(array $group_elements, array &$storage, array $fields) {
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
   * Save the breakpoints fields values to the storage.
   *
   * @param array $group_elements
   *   The submitted form values array.
   * @param array $storage
   *   An associative array containing the form storage.
   * @param array $fields
   *   The array of field names.
   */
  protected function saveBreakpointsStyleFormClassIndexBasedFields(array $group_elements, array &$storage, array $fields) {
    // Loop through the fields.
    foreach ($fields as $field_name) {
      // Loop through the breakpoints.
      foreach ($this->getBreakpointsKeys() as $breakpoint) {
        $storage[$field_name . '_' . $breakpoint] = [
          'class' => $this->getStyleOptionClassByIndex($field_name . '_' . $breakpoint, $group_elements[$field_name . '_' . $breakpoint]),
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
  protected function buildBreakpoints(array &$classes, array $storage, array $fields) {
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
