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
final class PatchArticleContentResource extends ResourceBase
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
      $container->get('logger.factory')->get('rest')
    );
  }

  /**
   * Responds to PATCH requests.
   */
  public function patch($nid, array $data): ModifiedResourceResponse
  {
    if (!$nid) {
      throw new NotFoundHttpException();
    }
    $title = $data['title'];
    $body = $data['body'];
    $field_content_access_restriction = $data['field_content_access_restriction'];
    $field_tags = $data['field_tags'];
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    if ($node) {
      try {
        $node->set('title', $title);
        $node->set('body', $body);
        $node->set('field_content_access_restriction', $field_content_access_restriction);
        $node->set('field_tags', $field_tags);
        if ($node->save()) {
          $this->logger->notice('Artcile with id @id updated successfully.', ['@id' => $nid]);
        }
      } catch (\Exception $e) {
        $this->logger->error('Article update failed:' . $e);
      }
      $this->logger->notice('The patch an article content record @id has been updated.', ['@id' => $nid]);
      $response = [
        'message' => "Article with id $nid updated successfully."
      ];
      return new ModifiedResourceResponse($response, 200);
    } else {
      $this->logger->notice('Article with nid @nid does not exist.', ['@nid' => $nid]);
      $response = [
        'message' => "Article with nid $nid does not exist."
      ];
      return new ModifiedResourceResponse($response, 404);
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
