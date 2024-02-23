<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents Delete Article Content records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_delete_article_content",
 *   label = @Translation("Delete Article Content"),
 *   uri_paths = {
 *     "canonical" = "/api/delete-article-content/{nid}",
 *   }
 * )
 *
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class DeleteArticleContentResource extends ResourceBase
{

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
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
    );
  }


  /**
   * Responds to DELETE requests.
   */
  public function delete($nid): ModifiedResourceResponse
  {
    if (!$nid) {
      throw new NotFoundHttpException();
    }
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    // Check if node exists with the given nid.
    if ($node && !empty($node)) {
      $node->delete();
      $this->logger->notice('The content record @id has been deleted.', ['@id' => $nid]);
      $response = [
        'message' => "Node $nid has been deleted successfully."
      ];
      return new ModifiedResourceResponse($response, 200);
    } else {
      return new ModifiedResourceResponse('Node not found', 404);
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
}
