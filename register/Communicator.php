<?php

namespace link_for_shopify_and_wishlist_member\register;  

class Communicator
{
  public function get_registration_info($key) {
    $shopify_app_url = \link_for_shopify_and_wishlist_member\Config::SHOPIFY_APP_URL();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$shopify_app_url}/membership_api/v1/registrations/{$key}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    $output = json_decode($output);
    if ($output === null) {
      return false;
    }
    
    return $output;
  }

  public function markTokenUsed($user_id, $token) {
    $shopify_app_url = \link_for_shopify_and_wishlist_member\Config::SHOPIFY_APP_URL();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$shopify_app_url}/membership_api/v1/registrations/mark_redeemed/{$token}");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('redeemed_by' => $user_id)));
    curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($http_status == 204 || $http_status == 404);
  }
}
