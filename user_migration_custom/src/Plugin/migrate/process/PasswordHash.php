<?php

namespace Drupal\user_migration_custom\Plugin\migrate\process;

use Drupal\Core\Password\PasswordInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Convert plain password to Drupal hash during user migration.
 *
 * @MigrateProcessPlugin(
 *   id = "password_hash"
 * )
 */
class PasswordHash extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The password hashing service.
   *
   * @var \Drupal\Core\Password\PasswordInterface
   */
  protected $passwordHasher;

  /**
   * Migration.
   *
   * @var \Drupal\migrate\Entity\MigrationInterface
   */
  protected $migration;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Password\PasswordInterface $password_hasher
   *   The password hasher service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PasswordInterface $password_hasher) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->passwordHasher = $password_hasher;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('password')
    );
  }

  /**
   * Tranform will recieve the value , process it and return back.
   *
   * @param mixed $value
   *   The value to be transformed.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The migration in which this process is being executed.
   * @param \Drupal\migrate\Row $row
   *   The row from the source to process.
   * @param string $destination_property
   *   The destination property currently worked on.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Ensure the password is not empty.
    if (!empty($value)) {
      // Hash the password using Drupal's password hashing function.
      $hashed_password = $this->passwordHasher->hash($value);
      return $hashed_password;
    }
    else {
      // Return NULL if the password is empty.
      return NULL;
    }
  }

}
