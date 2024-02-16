<?php

declare(strict_types=1);

namespace Drupal\custom_block_plugin\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a stock market data block.
 *
 * @Block(
 *   id = "custom_block_plugin_stock_market_data",
 *   admin_label = @Translation("Stock Market Data"),
 *   category = @Translation("Custom"),
 * )
 */
final class StockMarketDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'api_key' => $this->t(''),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $this->configuration['api_key'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['api_key'] = $form_state->getValue('api_key');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {

    $api_key = $this->configuration['api_key'];
    $stock_service = \Drupal::service('custom_block_plugin.stock_market_service');
    $result = $stock_service->getStockMarketData($api_key);

    // Use our theme function to render twig template.
    $build['content'] = [
      '#theme' => 'stock_market',
      '#json_data' => isset($result) ? json_decode($result, TRUE) : '',
      '#attached' => [
        'library' => [
          'custom_block_plugin/stock-market',
        ],
      ],
    ];
    $build['#cache']['max-age'] = 0;
    // $build['#cache']['max-age'] = 3600; //cache for 1 hour
    return $build;
  }

}
