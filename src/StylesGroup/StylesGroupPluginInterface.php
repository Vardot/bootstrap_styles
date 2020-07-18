<?php

namespace Drupal\bootstrap_styles\StylesGroup;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Provides an interface defining a Styles Group.
 */
interface StylesGroupPluginInterface extends PluginInspectionInterface, ContainerFactoryPluginInterface {

  /**
   * Return the title of the Styles Group form plugin.
   *
   * @return string
   *   The title of styles group.
   */
  public function getTitle();

}
