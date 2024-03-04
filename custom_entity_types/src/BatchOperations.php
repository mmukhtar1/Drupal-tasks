<?php

namespace Drupal\custom_entity_types;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Process the nodes provided in batch.
 */
class BatchOperations {
  use StringTranslationTrait;
  /**
   * Include the messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */

  protected $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(
        MessengerInterface $messenger,
        LoggerChannelFactory $loggerFactory,
        EntityTypeManagerInterface $entity_type_manager,
        TranslationInterface $string_translation
    ) {
    $this->messenger = $messenger;
    $this->loggerFactory = $loggerFactory->get('custom_entity_types');
    $this->entityTypeManager = $entity_type_manager;
    $this->stringTranslation = $string_translation;
  }

  /**
   * Unpbublish the nodes by batch.
   */
  public static function unPublishNode($nids, &$context) {
    $message = 'Unpublishing Node...';
    $results = [];
    foreach ($nids as $nid) {
      $storage = \Drupal::entityTypeManager()->getStorage('custom_entity_types');
      $node = $storage->load($nid);
      $results[] = $node->set('status', FALSE)->save();
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * Anonymises the nodes by batch.
   */
  public static function anonymizeNode($nids, &$context) {
    $message = 'Anonymizing Node...';
    $results = [];
    foreach ($nids as $nid) {
      $storage = \Drupal::entityTypeManager()->getStorage('custom_entity_types');
      $node = $storage->load($nid);
      $results[] = $node->set('uid', 0)->save();
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * Deletes the nodes by batch.
   */
  public static function deleteNode($nids, &$context) {
    $message = 'Deleting Node...';
    $results = [];
    foreach ($nids as $nid) {
      $storage = \Drupal::entityTypeManager()->getStorage('custom_entity_types');
      $node = $storage->load($nid);
      $results[] = $node->delete();
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * Unpublish node finished callback.
   */
  public static function unPublishNodeFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
            count($results),
            'One post processed.',
            '@count posts processed.'
        );
      \Drupal::logger('custom_entity_types')->notice($message);
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addMessage($message);
  }

  /**
   * Anonymise node finished callback.
   */
  public function anonymizeNodeFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = $this->stringTranslation->formatPlural(
            count($results),
            'One post processed.',
            '@count posts processed.'
        );
      $this->loggerFactory->notice($message);
    }
    else {
      $message = $this->t('Finished with an error.');
    }
  }

}
