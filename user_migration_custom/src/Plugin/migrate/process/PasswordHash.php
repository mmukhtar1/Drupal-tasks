<?php

namespace Drupal\user_migration_custom\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Convert plain password to Drupal hash during user migration.
 *
 * @MigrateProcessPlugin(
 *   id = "password_hash"
 * )
 */
class PasswordHash extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Ensure the password is not empty.
    if (!empty($value)) {
      // Hash the password using Drupal's password hashing function.
      $hashed_password = \Drupal::service('password')->hash($value);
      return $hashed_password;
    }
    else {
      // Return NULL if the password is empty.
      return NULL;
    }
  }

}
