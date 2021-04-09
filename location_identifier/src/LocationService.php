<?php

namespace Drupal\location_identifier;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Cache\Cache;

/**
 * Defines an importer of aggregator items.
 */
class LocationService {

  /**
   * The aggregator.settings config object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs an Importer object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('location.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation() {
    $cacheData = \Drupal::cache()->get('location_time');
    if (empty($cacheData)) {
      $location = [];
      if (!empty($this->config->get('country'))) {
        $location['country'] = $this->config->get('country');
      }
      if (!empty($this->config->get('city'))) {
        $location['city'] = $this->config->get('city');
      }
      if (!empty($this->config->get('timezone'))) {
        $date = new DrupalDateTime();
        $date->setTimezone(new \DateTimeZone($this->config->get('timezone')));
        $location['time'] = $date->format('jS M Y - g:i A');
      }
      \Drupal::cache()->set('location_time', $location, Cache::PERMANENT, ['location_time_tag']);
      return $location;
    }
    else {
      return $cacheData->data;
    }
  }

}
