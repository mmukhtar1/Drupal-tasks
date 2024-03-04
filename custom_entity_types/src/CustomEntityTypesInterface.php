<?php

declare(strict_types = 1);

namespace Drupal\custom_entity_types;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a custom entity types entity type.
 */
interface CustomEntityTypesInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
