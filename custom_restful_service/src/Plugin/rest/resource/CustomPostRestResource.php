<?php

declare(strict_types=1);

namespace Drupal\custom_restful_service\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Represents Custom Post Rest Resource records as resources.
 *
 * @RestResource (
 *   id = "custom_restful_service_custom_post_rest_resource",
 *   label = @Translation("Custom Post Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/create-article/",
 *   }
 * )
 *
 * @DCG
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class CustomPostRestResource extends ResourceBase
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
    $data_restriction = [
      'target_id' => 12,
      'target_id' => 13,
    ];

    $data_tags = [
      'target_id' => 1,
    ];

    $data = [
      'type' => 'article',
      'title' => 'My new article title',
      'uid' => 1,
      'body' => 'Iaceo magna patria. Plaga quae sino sit utinam volutpat. Enim eum feugiat jugis modo neque olim similis vicis',
      'field_content_access_restriction' => json_encode($data_restriction),
      'field_tags' => json_encode($data_tags),
    ];
    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->create($data);
    if ($node->save()) {
      \Drupal::messenger()->addMessage('Created new article');
      $this->logger->notice('Created new article with id @id.', ['@id' => $node->nid]);
    }
    $response = [
      'message' => 'Article created successfully'
    ];
    // Return the newly created record in the response body.
    return new ModifiedResourceResponse($response, 201);
  }
}
