<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents Patch an article content records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_patch_an_article_content",
 *   label = @Translation("Patch an article content"),
 *   uri_paths = {
 *     "canonical" = "/api/patch-article-content/{nid}",
 *   }
 * )
 *
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class PatchArticleContentResource extends ResourceBase {

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
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityTypeManagerInterface $entity_type_manager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Constructs a Drupal\rest\Plugin\rest\resource\EntityResource object.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Responds to PATCH requests.
   */
  public function patch($nid, array $data): ModifiedResourceResponse {
    if (!$nid) {
      throw new NotFoundHttpException();
    }
    $title = $data['title'];
    $body = $data['body'];
    $field_content_access_restriction = $data['field_content_access_restriction'];
    $field_tags = $data['field_tags'];
    $node = $this->entityTypeManager->getStorage('node')->load($nid);
    if ($node) {
      try {
        $node->set('title', $title);
        $node->set('body', $body);
        $node->set('field_content_access_restriction', $field_content_access_restriction);
        $node->set('field_tags', $field_tags);
        if ($node->save()) {
          $this->logger->notice('Artcile with id @id updated successfully.', ['@id' => $nid]);
        }
      }
      catch (\Exception $e) {
        $this->logger->error('Article update failed:' . $e);
      }
      $this->logger->notice('The patch an article content record @id has been updated.', ['@id' => $nid]);
      $response = [
        'message' => "Article with id $nid updated successfully.",
      ];
      return new ModifiedResourceResponse($response, 200);
    }
    else {
      $this->logger->notice('Article with nid @nid does not exist.', ['@nid' => $nid]);
      $response = [
        'message' => "Article with nid $nid does not exist.",
      ];
      return new ModifiedResourceResponse($response, 404);
    }
  }

  /**
   * Gets the base route.
   */
  protected function getBaseRoute($canonical_path, $method): Route {
    $route = parent::getBaseRoute($canonical_path, $method);
    // Set ID validation pattern.
    if ($method !== 'POST') {
      $route->setRequirement('id', '\d+');
    }
    return $route;
  }

}
