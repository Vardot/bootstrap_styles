<?php

namespace Drupal\bootstrap_styles\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * AJAX command for invoking an arbitrary jQuery method.
 *
 * The 'invoke' command will instruct the client to invoke the given jQuery
 * method with the supplied arguments on the elements matched by the given
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
   * An optional list of arguments to pass to the method.
   *
   * @var array
   */
  protected $arguments;

  /**
   * Constructs an RefreshResponsive object.
   *
   * @param string $selector
   *   A jQuery selector.
   * @param string $method
   *   The name of a jQuery method to invoke.
   * @param array $arguments
   *   An optional array of arguments to pass to the method.
   */
  public function __construct($selector, $method, array $arguments = []) {
    $this->selector = $selector;
    $this->method = $method;
    $this->arguments = $arguments;
  }

  /**
   * Implements Drupal\Core\Ajax\CommandInterface:render().
   */
  public function render() {
    return [
      'command' => 'bs_refresh_responsive',
      'selector' => $this->selector,
      'method' => NULL,
      'args' => $this->arguments,
    ];
  }

}
