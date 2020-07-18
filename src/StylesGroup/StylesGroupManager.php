<?php

namespace Drupal\bootstrap_styles\StylesGroup;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\bootstrap_styles\Style\StylePluginManagerInterface;

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
    // @TODO
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
    // @TODO
    uasort($styles, ['Drupal\Component\Utility\SortArray', 'sortByWeightElement']);
    return $styles;
  }

}
