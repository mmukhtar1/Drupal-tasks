<?php

declare(strict_types=1);

namespace Drupal\weather_forecast\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Local Weather Forecast routes.
 */
final class WeatherForecastController extends ControllerBase {

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

  /**
   * Show the forecast for a city.
   */
  public function showForecast() {

    $config = $this->configFactory->get('weather_forecast.settings');
    $city = $config->get('city');
    $increments = $config->get('increments');

    $forecaster = \Drupal::service('weather_forecast.forecast');
    $forecast = $forecaster->getByCity($city, $increments);
    if (!empty($forecast->list) && !empty($forecast->city)) {
      $hourly_forecast = $forecast->list;
      $timezone = timezone_name_from_abbr('', $forecast->city->timezone, 0);
    }

    // Use our theme function to render twig template.
    $build['content'] = [
      '#theme' => 'weather_forecast',
      '#city' => $forecast->city->name ?? '',
      '#timezone' => $timezone ?? '',
      '#forecast' => $hourly_forecast ?? '',
    ];
    $build['#cache']['max-age'] = 0;
    $build['content']['#attached']['library'][] = 'weather_forecast/weather-forecast';

    return $build;
  }

}
