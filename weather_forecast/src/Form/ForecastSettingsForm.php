<?php

namespace Drupal\weather_forecast\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class ForecastSettingsForm extends ConfigFormBase {
  const CONFIGNAME = 'weather_forecast.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_forecast_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::CONFIGNAME);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('api_key'),
    ];

    $cities = [
      'New York' => 'New York',
      'Los Angeles' => 'Los Angeles',
      'Chicago' => 'Chicago',
      'Houston' => 'Houston',
      'Phoenix' => 'Phoenix',
      'Philadelphia' => 'Philadelphia',
      'San Antonio' => 'San Antonio',
      'San Diego' => 'San Diego',
      'Dallas' => 'Dallas',
      'San Jose' => 'San Jose',
      'Austin' => 'Austin',
      'Jacksonville' => 'Jacksonville',
      'Fort Worth' => 'Fort Worth',
      'Columbus' => 'Columbus',
      'San Francisco' => 'San Francisco',
      'Charlotte' => 'Charlotte',
      'Indianapolis' => 'Indianapolis',
      'Seattle' => 'Seattle',
      'Denver' => 'Denver',
      'Washington' => 'Washington',
      'Boston' => 'Boston',
      'El Paso' => 'El Paso',
      'Detroit' => 'Detroit',
      'Nashville' => 'Nashville',
      'Portland' => 'Portland',
      'Memphis' => 'Memphis',
      'Oklahoma City' => 'Oklahoma City',
      'Las Vegas' => 'Las Vegas',
      'Louisville' => 'Louisville',
      'Baltimore' => 'Baltimore',
      'Milwaukee' => 'Milwaukee',
      'Albuquerque' => 'Albuquerque',
      'Tucson' => 'Tucson',
      'Fresno' => 'Fresno',
      'Mesa' => 'Mesa',
      'Sacramento' => 'Sacramento',
      'Atlanta' => 'Atlanta',
      'Kansas City' => 'Kansas City',
      'Colorado Springs' => 'Colorado Springs',
      'Miami' => 'Miami',
      'Raleigh' => 'Raleigh',
      'Omaha' => 'Omaha',
      'Long Beach' => 'Long Beach',
      'Virginia Beach' => 'Virginia Beach',
      'Oakland' => 'Oakland',
      'Minneapolis' => 'Minneapolis',
      'Tulsa' => 'Tulsa',
      'Arlington' => 'Arlington',
      'Tampa' => 'Tampa',
      'New Orleans' => 'New Orleans',
      'Wichita' => 'Wichita',
      'Cleveland' => 'Cleveland',
      'Bakersfield' => 'Bakersfield',
      'Aurora' => 'Aurora',
      'Anaheim' => 'Anaheim',
      'Honolulu' => 'Honolulu',
      'Santa Ana' => 'Santa Ana',
      'Riverside' => 'Riverside',
      'Corpus Christi' => 'Corpus Christi',
      'Lexington' => 'Lexington',
      'Stockton' => 'Stockton',
      'Henderson' => 'Henderson',
      'Saint Paul' => 'Saint Paul',
      'St. Louis' => 'St. Louis',
      'Cincinnati' => 'Cincinnati',
      'Pittsburgh' => 'Pittsburgh',
      'Greensboro' => 'Greensboro',
      'Anchorage' => 'Anchorage',
      'Plano' => 'Plano',
      'Lincoln' => 'Lincoln',
      'Orlando' => 'Orlando',
      'Irvine' => 'Irvine',
      'Newark' => 'Newark',
      'Toledo' => 'Toledo',
      'Durham' => 'Durham',
      'Chula Vista' => 'Chula Vista',
      'Fort Wayne' => 'Fort Wayne',
      'Jersey City' => 'Jersey City',
      'St. Petersburg' => 'St. Petersburg',
      'Laredo' => 'Laredo',
      'Madison' => 'Madison',
      'Chandler' => 'Chandler',
      'Buffalo' => 'Buffalo',
      'Lubbock' => 'Lubbock',
      'Scottsdale' => 'Scottsdale',
      'Reno' => 'Reno',
      'Glendale' => 'Glendale',
      'Gilbert' => 'Gilbert',
      'Winston–Salem' => 'Winston–Salem',
      'North Las Vegas' => 'North Las Vegas',
      'Norfolk' => 'Norfolk',
      'Chesapeake' => 'Chesapeake',
      'Garland' => 'Garland',
      'Irving' => 'Irving',
      'Hialeah' => 'Hialeah',
      'Fremont' => 'Fremont',
      'Boise' => 'Boise',
      'Richmond' => 'Richmond',
      'Baton Rouge' => 'Baton Rouge',
      'Spokane' => 'Spokane',
      'Des Moines' => 'Des Moines',
      'Tacoma' => 'Tacoma',
      'San Bernardino' => 'San Bernardino',
      'Modesto' => 'Modesto',
      'Fontana' => 'Fontana',
      'Santa Clarita' => 'Santa Clarita',
      'Birmingham' => 'Birmingham',
      'Oxnard' => 'Oxnard',
      'Fayetteville' => 'Fayetteville',
      'Moreno Valley' => 'Moreno Valley',
      'Rochester' => 'Rochester',
      'Glendale' => 'Glendale',
      'Huntington Beach' => 'Huntington Beach',
      'Salt Lake City' => 'Salt Lake City',
      'Grand Rapids' => 'Grand Rapids',
      'Amarillo' => 'Amarillo',
      'Yonkers' => 'Yonkers',
      'Aurora' => 'Aurora',
      'Montgomery' => 'Montgomery',
      'Akron' => 'Akron',
      'Little Rock' => 'Little Rock',
      'Huntsville' => 'Huntsville',
      'Augusta' => 'Augusta',
      'Port St. Lucie' => 'Port St. Lucie',
      'Grand Prairie' => 'Grand Prairie',
      'Columbus' => 'Columbus',
      'Tallahassee' => 'Tallahassee',
      'Overland Park' => 'Overland Park',
      'Tempe' => 'Tempe',
      'McKinney' => 'McKinney',
      'Mobile' => 'Mobile',
      'Cape Coral' => 'Cape Coral',
      'Shreveport' => 'Shreveport',
      'Frisco' => 'Frisco',
      'Knoxville' => 'Knoxville',
      'Worcester' => 'Worcester',
      'Brownsville' => 'Brownsville',
      'Vancouver' => 'Vancouver',
      'Fort Lauderdale' => 'Fort Lauderdale',
      'Sioux Falls' => 'Sioux Falls',
      'Ontario' => 'Ontario',
      'Chattanooga' => 'Chattanooga',
      'Providence' => 'Providence',
      'Newport News' => 'Newport News',
      'Rancho Cucamonga' => 'Rancho Cucamonga',
      'Santa Rosa' => 'Santa Rosa',
      'Oceanside' => 'Oceanside',
      'Salem' => 'Salem',
      'Elk Grove' => 'Elk Grove',
      'Garden Grove' => 'Garden Grove',
      'Pembroke Pines' => 'Pembroke Pines',
      'Peoria' => 'Peoria',
      'Eugene' => 'Eugene',
      'Corona' => 'Corona',
      'Cary' => 'Cary',
      'Springfield' => 'Springfield',
      'Fort Collins' => 'Fort Collins',
      'Jackson' => 'Jackson',
      'Alexandria' => 'Alexandria',
      'Hayward' => 'Hayward',
      'Lancaster' => 'Lancaster',
      'Lakewood' => 'Lakewood',
      'Clarksville' => 'Clarksville',
      'Palmdale' => 'Palmdale',
      'Salinas' => 'Salinas',
      'Springfield' => 'Springfield',
      'Hollywood' => 'Hollywood',
      'Pasadena' => 'Pasadena',
      'Sunnyvale' => 'Sunnyvale',
      'Macon' => 'Macon',
      'Kansas City' => 'Kansas City',
      'Pomona' => 'Pomona',
      'Escondido' => 'Escondido',
      'Killeen' => 'Killeen',
      'Naperville' => 'Naperville',
      'Joliet' => 'Joliet',
      'Bellevue' => 'Bellevue',
      'Rockford' => 'Rockford',
      'Savannah' => 'Savannah',
      'Paterson' => 'Paterson',
      'Torrance' => 'Torrance',
      'Bridgeport' => 'Bridgeport',
      'McAllen' => 'McAllen',
      'Mesquite' => 'Mesquite',
      'Syracuse' => 'Syracuse',
      'Midland' => 'Midland',
      'Pasadena' => 'Pasadena',
      'Murfreesboro' => 'Murfreesboro',
      'Miramar' => 'Miramar',
      'Dayton' => 'Dayton',
      'Fullerton' => 'Fullerton',
      'Olathe' => 'Olathe',
      'Orange' => 'Orange',
      'Thornton' => 'Thornton',
      'Roseville' => 'Roseville',
      'Denton' => 'Denton',
      'Waco' => 'Waco',
      'Surprise' => 'Surprise',
      'Carrollton' => 'Carrollton',
      'West Valley City' => 'West Valley City',
    ];

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the city'),
      '#options' => $cities,
      '#default_value' => $config->get('city'),
    ];

    $form['increments'] = [
      '#type' => 'number',
      '#title' => $this->t('Forecast Increments'),
      '#description' => $this->t('Up to how many forecast increments may be displayed at 3 hour intervals'),
      '#default_value' => $config->get('increments'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $config = $this->configFactory()->getEditable(self::CONFIGNAME);
    $config->set('api_key', $form_state->getValue('api_key'))
      ->set('city', $form_state->getValue('city'))
      ->set('increments', $form_state->getValue('increments'))
      ->save();

    // $this->config(self::CONFIGNAME)
    //   // Set the submitted configuration setting.
    //   ->set('api_key', $form_state->getValue('api_key'))
    //   ->set('city', $form_state->getValue('city'))
    //   ->set('increments', $form_state->getValue('increments'))
    //   ->save();
    parent::submitForm($form, $form_state);
  }

}
