<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;

/**
 * Represents Custom Get Rest Resource records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_custom_get_rest_resource",
 *   label = @Translation("Custom Get Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/custom-rest-resource/{nid}",
 *   }
 * )
 * 
 * 
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class CustomGetRestResource extends ResourceBase
{

  /**
   * The key-value storage.
   */
  private readonly KeyValueStoreInterface $storage;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    KeyValueFactoryInterface $keyValueFactory,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->storage = $keyValueFactory->get('custom_restful_service_custom_get_rest_resource');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self
  {

    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('keyvalue')
    );
  }


  /**
   * Responds to GET requests.
   */
  public function get($nid): ResourceResponse
  {
    if (!$nid) {
      throw new NotFoundHttpException();
    }
    if (!(\Drupal::currentUser())->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->condition('nid', $nid);
    $query->accessCheck(TRUE);

    $nids =  $query->execute();
    if ($nids) {
      $nodes =  Node::loadMultiple($nids);
      foreach ($nodes as $key => $value) {
        $data[] = [
          'id' => $value->id(),
          'title' => $value->getTitle(),
          'body' => $value->get('body')->getValue(),
          'content access restriction group' => $value->get('field_content_access_restriction')->getValue(),
          'Hierachial access' => $value->get('field_hierachial_access')->getValue(),
          'Tags' => $value->get('field_tags')->getValue(),
        ];
      }
    }

    $response = new ResourceResponse($data);
    // In order to generate fresh result every time (without clearing 
    // the cache), you need to invalidate the cache.
    if ($response) {
      $response->addCacheableDependency($data);
      return $response;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getBaseRoute($canonical_path, $method): Route
  {
    $route = parent::getBaseRoute($canonical_path, $method);
    // Set ID validation pattern.
    if ($method !== 'POST') {
      $route->setRequirement('id', '\d+');
    }
    return $route;
  }

  /**
   * Returns next available ID.
   */
  private function getNextId(): int
  {
    $ids = \array_keys($this->storage->getAll());
    return count($ids) > 0 ? max($ids) + 1 : 1;
  }

  public function permissions()
  {
    return [];
  }
}
