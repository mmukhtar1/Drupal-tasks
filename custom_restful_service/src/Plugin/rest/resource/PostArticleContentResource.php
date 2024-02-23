<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents Create Article Content records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_create_article_content",
 *   label = @Translation("Create Article Content"),
 *   uri_paths = {
 *     "canonical" = "/api/create-article-content/{id}",
 *     "create" = "/api/create-article-content"
 *   }
 * )
 * 
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class PostArticleContentResource extends ResourceBase
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
   * Responds to POST requests and saves the new record.
   */
  public function post(array $data): ModifiedResourceResponse
  {
    $data = [
      'type' => 'article',
      'title' => $data['title'] ?? 'No title',
      'body' => $data['body'],
      'field_content_access_restriction' => $data['field_content_access_restriction'] ?? [],
      'field_tags' => $data['field_tags'] ?? [],
      'uid' => 1,
      'status' => 1,
    ];
    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->create($data);
      
    if ($node->save()) {
      \Drupal::messenger()->addMessage('Created new article');
      $this->logger->notice('Created new article with id @id.', ['@id' => $node->id()]);
    }
    $response = [
      'message' => 'Article created successfully'
    ];
    // Return the newly created record in the response body.
    return new ModifiedResourceResponse($response, 200);
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
