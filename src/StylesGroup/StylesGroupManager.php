<?php

namespace Drupal\bootstrap_styles\StylesGroup;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\bootstrap_styles\Style\StylePluginManagerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an StylesGroup plugin manager.
 */
class StylesGroupManager extends DefaultPluginManager {

  /**
   * The style plugin manager interface.
   *
   * @var \Drupal\bootstrap_styles\Style\StylePluginManagerInterface
   */
  protected $styleManager;

  /**
   * Constructs a StylesGroupManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   * @param \Drupal\bootstrap_styles\Style\StylePluginManagerInterface $style_manager
   *   The style plugin manager interface.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler, StylePluginManagerInterface $style_manager) {
    parent::__construct(
      'Plugin/BootstrapStyles/StylesGroup',
      $namespaces,
      $module_handler,
      'Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginInterface',
      'Drupal\bootstrap_styles\Annotation\StylesGroup'
    );
    $this->alterInfo('bootstrap_styles_info');
    $this->setCacheBackend($cache_backend, 'bootstrap_styles_groups');
    $this->styleManager = $style_manager;
  }

  /**
   * Returns an array of styles groups.
   *
   * @return array
   *   Returns a nested array of styles keyed by styles group.
   */
  public function getStylesGroups() {
    $groups = [];
    foreach ($this->getDefinitions() as $group_id => $group_definition) {
      $groups[$group_id] = $group_definition;
      $groups[$group_id]['styles'] = $this->getGroupStyles($group_id);
    }
    uasort($groups, ['Drupal\Component\Utility\SortArray', 'sortByWeightElement']);
    return $groups;
  }

  /**
   * Returns an array of group styles.
   *
   * @param string $group_id
   *   The styles group plugin id.
   *
   * @return array
   *   Returns an array of styles definitions of specific group.
   */
  public function getGroupStyles($group_id) {
    $styles = [];
    foreach ($this->styleManager->getDefinitions() as $style_id => $style_definition) {
      if ($style_definition['group_id'] == $group_id) {
        $styles[$style_id] = $style_definition;
      }
    }
    uasort($styles, ['Drupal\Component\Utility\SortArray', 'sortByWeightElement']);
    return $styles;
  }

  /**
   * 
   */
  public function buildStylesFormElements(array &$form, FormStateInterface $form_state, $storage) {
    foreach ($this->getStylesGroups() as $group_key => $style_group) {
      // Styles Group.
      if (isset($style_group['styles'])) {
        $form[$group_key] = [
          '#type' => 'details',
          '#title' => $style_group['title']->__toString(),
          '#open' => TRUE,
          '#tree' => TRUE,
        ];

        foreach ($style_group['styles'] as $style_key => $style) {
          $style_instance = $this->styleManager->createInstance($style_key);
          $form[$group_key] += $style_instance->buildStyleFormElements($form[$group_key], $form_state, $storage);
        }
      }
    }
    return $form;
  }

  /**
   * 
   */
  public function submitStylesFormElements(array &$form, FormStateInterface $form_state, $tree, $storage) {
    $options = [];
    foreach ($this->getStylesGroups() as $group_key => $style_group) {
      // Styles Group.
      if ($form_state->getValue(array_merge($tree, [$group_key]))) {
        $group_elements = $form_state->getValue(array_merge($tree, [$group_key]));
        foreach ($group_elements as $style_key => $style) {
          $style_instance = $this->styleManager->createInstance($style_key);
          $options += $style_instance->submitStyleFormElements($group_elements);
        }
      }
    }

    return array_merge($storage, $options);
  }

  /**
   * @param $element
   * @param $plugins_storage
   */
  public function buildStyles(array $element, array $plugins_storage, $theme_wrapper = NULL) {
    foreach ($plugins_storage as $plugin_id => $storage) {
      // Handle special cases.
      // Ignore background color if there's a background media.
      if (isset($plugins_storage['background_media']['media_id']) && $plugin_id == 'background_color') {
        continue;
      }
      $style_instance = $this->styleManager->createInstance($plugin_id);
      $element = $style_instance->build($element, $storage, $theme_wrapper);
    }
    return $element;
  }

}
