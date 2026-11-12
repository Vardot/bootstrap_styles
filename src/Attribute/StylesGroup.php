<?php

namespace Drupal\bootstrap_styles\Attribute;

use Drupal\Component\Plugin\Attribute\Plugin;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a Bootstrap Styles styles group plugin attribute.
 *
 * @see \Drupal\bootstrap_styles\StylesGroup\StylesGroupManager
 * @see \Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginInterface
 * @see \Drupal\bootstrap_styles\Annotation\StylesGroup
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class StylesGroup extends Plugin {

  /**
   * Constructs a StylesGroup attribute.
   *
   * @param string $id
   *   The plugin ID.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $title
   *   (optional) The title of the styles group plugin.
   * @param int|null $weight
   *   (optional) An integer to determine the weight of this styles group.
   * @param string|null $icon
   *   (optional) The icon path, starting with the machine name of the module
   *   that provides it, for example "my_module/images/my-icon.svg".
   * @param class-string|null $deriver
   *   (optional) The deriver class.
   */
  public function __construct(
    public readonly string $id,
    public readonly ?TranslatableMarkup $title = NULL,
    public readonly ?int $weight = NULL,
    public readonly ?string $icon = NULL,
    public readonly ?string $deriver = NULL,
  ) {}

}
