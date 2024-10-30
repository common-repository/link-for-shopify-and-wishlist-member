<?php

namespace link_for_shopify_and_wishlist_member\register;

class TokenUsage {

  /**
   * adds a record to the user_meta table with with the token and a dash
   * to indicate that the token has been marked as used
   */
  public static function recordTokenUsed($_logged_in_cookie, $_expire, $_expiration, $user_id, $_logged_in) {
    if (!function_exists('wlmapi_get_member_levels')) { // prevents this function from preventing logging in if wlm is disabled
      return;
    }
    $wlmLevels = wlmapi_get_member_levels($user_id);

    $communicator = new Communicator();
    foreach ($wlmLevels as $wlmLevel) {
      $token = $wlmLevel->TxnID;
      if (self::isTokenMarkedAsUsed($user_id, $token)) {
        continue;
      }

      $wasTokenMarkedSuccessfully = $communicator->markTokenUsed($user_id, $token);
      if ($wasTokenMarkedSuccessfully) {
        self::markTokenRecordUsed($user_id, $token);
      }
    }
  }

  private static function isTokenMarkedAsUsed($user_id, $token) {
    $token_meta = get_user_meta($user_id, $token, true);
    return $token_meta !== ''; // empty string if meta value does not exist
  }

  private static function markTokenRecordUsed($user_id, $token) {
    add_user_meta($user_id, $token, '-', true); // true means don't add the same key twice
  }

}
