<?php

declare(strict_types = 1);

namespace Drupal\custom_entity_types\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\custom_entity_types\CustomEntityTypesInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the custom entity types entity class.
 *
 * @ContentEntityType(
 *   id = "custom_entity_types",
 *   label = @Translation("Custom entity types"),
 *   label_collection = @Translation("Custom entity typess"),
 *   label_singular = @Translation("custom entity types"),
 *   label_plural = @Translation("custom entity typess"),
 *   label_count = @PluralTranslation(
 *     singular = "@count custom entity typess",
 *     plural = "@count custom entity typess",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\custom_entity_types\CustomEntityTypesListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\custom_entity_types\CustomEntityTypesAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\custom_entity_types\Form\CustomEntityTypesForm",
 *       "edit" = "Drupal\custom_entity_types\Form\CustomEntityTypesForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "custom_entity_types",
 *   admin_permission = "administer custom_entity_types",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/custom-entity-types",
 *     "add-form" = "/custom-entity-types/add",
 *     "canonical" = "/custom-entity-types/{custom_entity_types}",
 *     "edit-form" = "/custom-entity-types/{custom_entity_types}/edit",
 *     "delete-form" = "/custom-entity-types/{custom_entity_types}/delete",
 *     "delete-multiple-form" = "/admin/content/custom-entity-types/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.custom_entity_types.settings",
 * )
 */
final class CustomEntityTypes extends ContentEntityBase implements CustomEntityTypesInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);
    // Label.
    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    // Description.
    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('view', TRUE);
    // Type.
    $fields['type'] = BaseFieldDefinition::create('list_string')
      ->setSettings([
        'allowed_values' => ['content' => 'content', 'configuration' => 'configuration'],
      ])
      ->setLabel('Type')
      ->setDescription('Select type')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'list_string',
        'label' => 'above',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE);
    // Enabled.
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 5,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);
    // Author.
    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);
    // Authored on.
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the custom entity types was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the custom entity types was last edited.'));

    return $fields;
  }

}
