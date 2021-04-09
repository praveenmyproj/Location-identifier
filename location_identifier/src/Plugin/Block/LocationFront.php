<?php

namespace Drupal\location_identifier\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'Location & Time' block.
 *
 * @Block(
 *   id = "location_time_Block",
 *   admin_label = @Translation("Location & Time")
 * )
 */
class LocationFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $loc_details = \Drupal::service('location.items.service')->getLocation();
    $country = $city = $time = '';
    if (!empty($loc_details)) {
      $country = $loc_details['country'];
      $city = $loc_details['city'];
      $time = $loc_details['time'];
    }
    return [
      '#theme' => 'locationtimeblock',
      '#loc_country' => $country,
      '#loc_city' => $city,
      '#loc_time' => $time,
    ];
  }

  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), array('locationtimeblocktags'));
  }

}
