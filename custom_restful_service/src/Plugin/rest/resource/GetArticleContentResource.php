<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents Get Article Content Resource records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_custom_get_rest_resource",
 *   label = @Translation("GET the Article Contents"),
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
final class GetArticleContentResource extends ResourceBase {

  /**
   * Current user account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a Drupal\rest\Plugin\rest\resource\EntityResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountInterface $current_user,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
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
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {

    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Responds to GET requests.
   */
  public function get($nid): ResourceResponse {
    if (!$nid) {
      throw new NotFoundHttpException();
    }
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $nodes = $this->getNodeDetails($nid);
    if ($nodes) {
      foreach ($nodes as $value) {
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
    else {
      $data = [
        'message' => 'No Article found with node id specified.',
      ];
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
  protected function getBaseRoute($canonical_path, $method): Route {
    $route = parent::getBaseRoute($canonical_path, $method);
    // Set ID validation pattern.
    if ($method !== 'POST') {
      $route->setRequirement('id', '\d+');
    }
    return $route;
  }

  /**
   * Get the node details by passing the nid. As we are geting only.
   *
   *  One node, we are not caching the result.
   *
   * @param string $nid
   *   Node id.
   *
   * @return array
   *   Node data.
   */
  public function getNodeDetails($nid): array {
    $values = [
      'type' => 'article',
      'nid' => $nid,
    ];

    $nodes = $this->entityTypeManager
      ->getStorage('node')
      ->loadByProperties($values);

    if ($nodes) {
      return $nodes;
    }
    else {
      return [];
    }
  }

}
