<?php

namespace Drupal\bootstrap_styles\Style;

use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * A base class to help developers implement their own Styles Group plugins.
 */
abstract class StylePluginBase extends PluginBase implements StylePluginInterface {
  use StringTranslationTrait;

  /**
   * Config settings.
   *
   * @var string
   */
  const CONFIG = 'bootstrap_styles.settings';

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a StylePluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->pluginDefinition['tite'];
  }

  /**
   * {@inheritdoc}
   */
  public function config() {
    return $this->configFactory->getEditable(static::CONFIG);
  }

  /**
   * Helper function to get the options of given style name.
   *
   * @param string $name
   *   A config style name like background_color.
   *
   * @return array
   *   Array of key => value of style name options.
   */
  public function getStyleOptions(string $name) {
    $config = $this->config();
    $options = [];
    $config_options = $config->get($name);

    $options = ['_none' => $this->t('N/A')];
    $lines = explode(PHP_EOL, $config_options);
    foreach ($lines as $line) {
      $line = explode('|', $line);
      if ($line && isset($line[0]) && isset($line[1])) {
        $options[$line[0]] = $line[1];
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    return $build;
  }

}
