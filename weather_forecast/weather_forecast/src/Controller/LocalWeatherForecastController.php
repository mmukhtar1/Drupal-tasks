<?php

declare(strict_types=1);

namespace Drupal\weather_forecast\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Local Weather Forecast routes.
 */
final class LocalWeatherForecastController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function __invoke(): array
  {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

  /**
   * Show the forecast for a city.
   */
  public function show_forecast()
  {

    $config = \Drupal::config('weather_forecast.settings');
    $city = $config->get('city');
    $increments = $config->get('increments');

    $forecaster = \Drupal::service('weather_forecast.forecast');
    $forecast = $forecaster->getByCity($city, $increments);
    $hourly_forecast = $forecast->list;
    $timezone = timezone_name_from_abbr('', $forecast->city->timezone, 0);
    
    // use our theme function to render twig template
    $build['content'] = [
      '#theme' => 'weather_forecast',
      '#city' => $forecast->city->name,
      '#timezone' => $timezone,
      '#forecast' => $hourly_forecast,
    ];
    $build['#cache']['max-age'] = 0;
    $build['content']['#attached']['library'][] = 'weather_forecast/weather-forecast';

    return $build;
  }
}
