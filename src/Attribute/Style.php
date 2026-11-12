<?php

namespace Drupal\bootstrap_styles\Attribute;

use Drupal\Component\Plugin\Attribute\Plugin;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a Bootstrap Styles style plugin attribute.
 *
 * @see \Drupal\bootstrap_styles\Style\StyleManager
 * @see \Drupal\bootstrap_styles\Style\StylePluginInterface
 * @see \Drupal\bootstrap_styles\Annotation\Style
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Style extends Plugin {

  /**
   * Constructs a Style attribute.
   *
   * @param string $id
   *   The plugin ID.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $title
   *   (optional) The title of the style plugin.
   * @param string|null $group_id
   *   (optional) The ID of the styles group plugin this style belongs to.
   * @param int|null $weight
   *   (optional) An integer to determine the weight of this style.
   * @param class-string|null $deriver
   *   (optional) The deriver class.
   */
  public function __construct(
    public readonly string $id,
    public readonly ?TranslatableMarkup $title = NULL,
    public readonly ?string $group_id = NULL,
    public readonly ?int $weight = NULL,
    public readonly ?string $deriver = NULL,
  ) {}

}
