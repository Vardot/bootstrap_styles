<?php

namespace Drupal\bootstrap_styles\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * AJAX command for invoking an arbitrary jQuery method.
 *
 * The 'invoke' command will instruct the client to invoke the given jQuery
 * method with the supplied data on the elements matched by the given
 * selector. Intended for simple jQuery commands, such as attr(), addClass(),
 * removeClass(), toggleClass(), etc.
 */
class RefreshResponsive implements CommandInterface {

  /**
   * A CSS selector string.
   *
   * If the command is a response to a request from an #ajax form element then
   * this value can be NULL.
   *
   * @var string
   */
  protected $selector;

  /**
   * A jQuery method to invoke.
   *
   * @var string
   */
  protected $method;

  /**
   * An optional list of data to pass to the method.
   *
   * @var array
   */
  protected $data;

  /**
   * Constructs an RefreshResponsive object.
   *
   * @param string $selector
   *   A jQuery selector.
   * @param string $method
   *   The name of a jQuery method to invoke.
   * @param array $data
   *   An optional array of data to pass to the method.
   */
  public function __construct($selector, $method, array $data = []) {
    $this->selector = $selector;
    $this->method = $method;
    $this->data = $data;
  }

  /**
   * Implements Drupal\Core\Ajax\CommandInterface:render().
   */
  public function render() {
    return [
      'command' => 'bs_refresh_responsive',
      'selector' => $this->selector,
      'method' => NULL,
      'data' => $this->data,
    ];
  }

}
