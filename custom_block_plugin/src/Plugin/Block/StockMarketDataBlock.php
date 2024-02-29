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
   * Default configuration of the form.
   */
  public function defaultConfiguration(): array {
    return [
      'api_key' => $this->t(''),
    ];
  }

  /**
   * Configuration form for the block.
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $this->configuration['api_key'],
    ];
    $form['endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API EndPoint'),
      '#default_value' => $this->configuration['endpoint'],
    ];
    return $form;
  }

  /**
   * Block configuration form submit handler.
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['api_key'] = $form_state->getValue('api_key');
    $this->configuration['endpoint'] = $form_state->getValue('endpoint');
  }

  /**
   * Build render array for displaying the block .
   */
  public function build(): array {

    $api_key = $this->configuration['api_key'];
    $endpoint = $this->configuration['endpoint'];
    $stock_service = \Drupal::service('custom_block_plugin.stock_market_service');
    $result = $stock_service->getStockMarketData($api_key, $endpoint);

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
    return $build;
  }

}
