<?php

namespace Drupal\bootstrap_styles\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Class BackgroundMedia.
 *
 * @package Drupal\bootstrap_styles\Plugin\Style
 *
 * @Style(
 *   id = "background_media",
 *   title = @Translation("Background Media"),
 *   group_id = "background",
 *   weight = 2
 * )
 */
class BackgroundMedia extends StylePluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a BackgroundMedia object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, EntityTypeBundleInfoInterface $entity_type_bundle_info, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $config_factory);
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->config();

    // Background image media bundle.
    $media_bundles = [];
    $media_bundles_info = $this->entityTypeBundleInfo->getBundleInfo('media');
    // Ignore if match any of the following names.
    $disabled_bundles = [
      'audio',
      'audio_file',
      'instagram',
      'tweet',
      'document',
      'remote_video',
    ];

    foreach ($media_bundles_info as $key => $bundle) {
      if (!in_array($key, $disabled_bundles)) {
        $media_bundles[$key] = $bundle['label'] . ' (' . $key . ')';
      }
    }

    $form['background']['background_image_bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Image background media bundle'),
      '#options' => $media_bundles,
      '#description' => $this->t('Image background media entity bundle.'),
      '#default_value' => $config->get('background_image.bundle'),
      '#ajax' => [
        'callback' => __CLASS__ . '::getFields',
        'event' => 'change',
        'method' => 'html',
        'wrapper' => 'media_image_bundle_fields',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['background']['background_image_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Image background media field'),
      '#options' => $this->getFieldsByBundle($config->get('background_image.bundle')),
      '#description' => $this->t('Image background media entity field.'),
      '#default_value' => $config->get('background_image.field'),
      '#attributes' => ['id' => 'media_image_bundle_fields'],
      '#validated' => TRUE,
    ];

    $form['background']['background_local_video_bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Local video background media bundle'),
      '#options' => $media_bundles,
      '#description' => $this->t('Background for local video media entity bundle.'),
      '#default_value' => $config->get('background_local_video.bundle'),
      '#ajax' => [
        'callback' => __CLASS__ . '::getFields',
        'event' => 'change',
        'method' => 'html',
        'wrapper' => 'media_local_video_bundle_fields',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['background']['background_local_video_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Local video background media field'),
      '#options' => $this->getFieldsByBundle($config->get('background_local_video.bundle')),
      '#description' => $this->t('Local video background media entity field.'),
      '#default_value' => $config->get('background_local_video.field'),
      '#attributes' => ['id' => 'media_local_video_bundle_fields'],
      '#validated' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsByBundle($bundle) {
    $field_map = \Drupal::service('entity_field.manager')->getFieldMap();
    $media_field_map = $field_map['media'];
    $fields = [];
    foreach ($media_field_map as $field_name => $field_info) {
      if (
        in_array($bundle, $field_info['bundles']) &&
        in_array($field_info['type'], ['image', 'file']) &&
        $field_name !== 'thumbnail'
      ) {
        $fields[$field_name] = $field_name;
      }
    }
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getFields(array &$element, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $bundle = $triggering_element['#value'];
    $wrapper_id = $triggering_element["#ajax"]["wrapper"];
    $rendered_field = '';

    $field_map = \Drupal::service('entity_field.manager')->getFieldMap();
    $media_field_map = $field_map['media'];

    foreach ($media_field_map as $field_name => $field_info) {
      if (
        in_array($bundle, $field_info['bundles']) &&
        in_array($field_info['type'], ['image', 'file']) &&
        $field_name !== 'thumbnail'
      ) {
        $rendered_field .= '<option value="' . $field_name . '">' . $field_name . '</option>';
      }
    }

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#' . $wrapper_id, $rendered_field));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('background_image.bundle', $form_state->getValue('background_image_bundle'))
      ->set('background_image.field', $form_state->getValue('background_image_field'))
      ->set('background_local_video.bundle', $form_state->getValue('background_local_video_bundle'))
      ->set('background_local_video.field', $form_state->getValue('background_local_video_field'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array $form, FormStateInterface $form_state, $storage) {
    // Background media.
    $allowed_bundles = [];
    $config = $this->config();
    // Check if the bundle exist.
    if ($config->get('background_image.bundle') && $this->entityTypeManager->getStorage('media_type')->load($config->get('background_image.bundle'))) {
      $allowed_bundles[] = $config->get('background_image.bundle');
    }
    // Check if the bundle exist.
    if ($config->get('background_local_video.bundle') && $this->entityTypeManager->getStorage('media_type')->load($config->get('background_local_video.bundle'))) {
      $allowed_bundles[] = $config->get('background_local_video.bundle');
    }

    if ($allowed_bundles) {
      $form['background_media'] = [
        '#type' => 'media_library',
        '#title' => $this->t('Background media'),
        '#description' => $this->t('Background media'),
        '#allowed_bundles' => $allowed_bundles,
        '#default_value' => $storage['background_media']['media_id'],
        '#prefix' => '<hr />',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'background_media' => [
        'media_id' => $group_elements['background_media'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    $config = $this->config();
    if ($media_id = $storage['media_id']) {
      $media_entity = Media::load($media_id);
      if ($media_entity) {
        $bundle = $media_entity->bundle();

        if ($config->get('background_image.bundle') && $bundle == $config->get('background_image.bundle')) {
          $media_field_name = $config->get('background_image.field');
          // Check if the field exist.
          if ($media_entity->hasField($media_field_name)) {
            $background_image_style = $this->buildBackgroundMediaImage($media_entity, $media_field_name);
            // Assign the style to element or its theme wrapper if exist.
            if ($theme_wrapper && isset($build['#theme_wrappers'][$theme_wrapper])) {
              $build['#theme_wrappers'][$theme_wrapper]['#attributes']['style'][] = $background_image_style;
            }
            else {
              $build['#attributes']['style'][] = $background_image_style;
            }
          }
        }
        elseif ($config->get('background_local_video.bundle') && $bundle == $config->get('background_local_video.bundle')) {
          $media_field_name = $config->get('background_local_video.field');
          // Check if the field exist.
          if ($media_entity->hasField($media_field_name)) {
            $background_video_url = $this->buildBackgroundMediaLocalVideo($media_entity, $media_field_name);

            $build['#theme_wrappers']['bs_video_background'] = [
              '#video_background_url' => $background_video_url,
            ];
          }
        }
      }
    }

    return $build;
  }

  /**
   * Helper function to the background media image style.
   *
   * @param object $media_entity
   *   A media entity object.
   * @param object $field_name
   *   The Media entity local video field name.
   *
   * @return string
   *   Background media image style.
   */
  public function buildBackgroundMediaImage($media_entity, $field_name) {
    $fid = $media_entity->get($field_name)->target_id;
    $file = File::load($fid);
    $background_url = $file->createFileUrl();

    $style = 'background-image: url(' . $background_url . '); background-repeat: no-repeat; background-size: cover;';
    return $style;
  }

  /**
   * Helper function to the background media local video style.
   *
   * @param object $media_entity
   *   A media entity object.
   * @param object $field_name
   *   The Media entity local video field name.
   *
   * @return string
   *   Background media local video style.
   */
  public function buildBackgroundMediaLocalVideo($media_entity, $field_name) {
    $fid = $media_entity->get($field_name)->target_id;
    $file = File::load($fid);
    return $file->createFileUrl();
  }

}
