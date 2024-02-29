<?php

declare(strict_types=1);

namespace Drupal\custom_block_plugin\Service;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Messenger\MessengerInterface;
use GuzzleHttp\ClientInterface;

/**
 * Service Class to get Stock market data from external API.
 */
class StockMarketService {

  /**
   * The http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * StockMarketService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The http client.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   The logger factory.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   Messenger Object.
   */
  public function __construct(ClientInterface $client, LoggerChannelFactory $loggerFactory, MessengerInterface $messenger) {
    $this->client = $client;
    $this->loggerFactory = $loggerFactory->get('custom_block_plugin');
    $this->messenger = $messenger;
  }

  /**
   * Gets the stock market real time data from external API.
   *
   * @param string $api_key
   *   API key to connect to the endpoint.
   * @param string $endpoint
   *   Endpoint URL.
   *
   * @var \GuzzleHttp\Message\Response $result
   */
  public function getStockMarketData($api_key, $endpoint) {
    $request = $this->client->get(
      $endpoint,
      [
        'query' => [
          // Specify which all stocks to be listed here.
          'symbol' => 'AAPL,EUR/USD,IXIC,ETH/BTC:Huobi,TRP:TSX,INFY:NSE',
          'interval' => '1h',
          'apikey' => $api_key,
          'timezone' => 'Asia/Kolkata',
        ],
      ]
    );
    try {
      if (200 == $request->getStatusCode()) {
        $stock_data = $request->getBody()->getContents();
        return $stock_data;
      }
      else {
        $this->loggerFactory->notice("No data found!");
      }
    }
    catch (\Exception $e) {
      $this->messenger->addMessage(t("Could not retrieve the data: @message", ['@message' => $e->getMessage()]), 'error');
    }
  }

}
