<?php

namespace Drupal\bootstrap_styles\StylesGroup;

use Drupal\Component\Plugin\PluginBase;

/**
 * A base class to help developers implement their own Styles Group plugins.
 */
abstract class StylesGroupPluginBase extends PluginBase implements StylesGroupPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->pluginDefinition['title'];
  }

}
