<?php

declare(strict_types=1);

namespace Drupal\custom_block_plugin\Service;

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
   * StockMarketService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The http client.
   */
  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

  /**
   * Gets the stock market real time data.
   *
   * @var \GuzzleHttp\Message\Response $result
   */
  public function getStockMarketData($api_key) {
    // $request = $this->client->get(
    //   'https://api.twelvedata.com/time_series',
    //   [
    //     'query' => [
    //       // Specify which all stocks to be listed here.
    //       'symbol' => 'AAPL,EUR/USD,IXIC,ETH/BTC:Huobi,TRP:TSX,INFY:NSE',
    //       'interval' => '1h',
    //       'apikey' => $api_key,
    //       'timezone' => 'Asia/Kolkata',
    //     ],
    //   ]
    // );
    // try {
    //   if (200 == $request->getStatusCode()) {
    //     $stock_data = $request->getBody()->getContents();
    //     return $stock_data;
    //   }
    //   else {
    //     \Drupal::logger('custom_block_plugin')->notice("No data found!");
    //   }
    // }
    // catch (\Exception $e) {
    //   \Drupal::messenger()->addMessage(t("Could not retrieve the data:" . $e->getMessage()), 'error');
    // }
  }

}
