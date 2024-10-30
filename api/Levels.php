<?php
namespace link_for_shopify_and_wishlist_member\api;

class Levels {
  public function get_levels() {
    if (!function_exists('wlmapi_get_levels')) {
      return json_encode(array(
        'error' => 'Could not fetch levels.'
      ));
    }
    $levels = wlmapi_get_levels();
    return json_encode($levels['levels']['level']);
  }
}