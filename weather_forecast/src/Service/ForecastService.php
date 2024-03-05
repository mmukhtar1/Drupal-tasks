<?php

namespace Drupal\weather_forecast\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\ClientInterface;

/**
 * The weather forecast service. calls an external API and show.
 *
 *  The data.
 */
class ForecastService {
  use StringTranslationTrait;

  /**
   * Http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * ForecastService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   HTTP client.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   Config Factory.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger.
   */
  public function __construct(
    ClientInterface $client,
    ConfigFactoryInterface $configFactory,
    MessengerInterface $messenger
  ) {
    $this->client = $client;
    $this->configFactory = $configFactory;
    $this->messenger = $messenger;
  }

  /**
   * Get the API key helper function.
   */
  public function getApiKey() {
    $config = $this->configFactory->get('weather_forecast.settings');
    return $config->get('api_key');
  }

  /**
   * Get the weather forecast of the city given.
   *
   * @param string $city
   *   The city name.
   * @param int $cnt
   *   Number of intervals where value is taken.
   *
   * @return array
   *   The forcast result array.
   */
  public function getByCity($city, $cnt) {
    // Get data via http://openweathermap.org/api
    /** @var \GuzzleHttp\Message\Response $result */
    $config = $this->configFactory->get('weather_forecast.settings');
    $api_key = $config->get('api_key');
    try {
      $request = $this->client->get(
        'https://api.openweathermap.org/data/2.5/forecast',
        [
          'query' => [
            'q' => $city . ",us",
            'appid' => $api_key,
            'cnt' => $cnt,
            'units' => 'metric',
          ],
        ]
      );

      if (200 == $request->getStatusCode()) {
        $forecast = json_decode($request->getBody());
        return $forecast;
      }
    }
    catch (\Exception $e) {
      $message = $this->t(
        "Could not get a forecast for @city, please try again later:@error",
        [
          '@city' => $city,
          '@error' => $e->getMessage(),
        ]
          );
      $this->messenger->addMessage($message, 'error');
    }
  }

}
