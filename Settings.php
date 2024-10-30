<?php

namespace link_for_shopify_and_wishlist_member;

class Settings
{

  const PLUGIN_PREFIX = 'lswlm'; // acronym for Link for Shopify a WishList Member

  const USER_OPTIONS_ACTION = 'link_for_shopify_and_wlm_settings';

  public static function add_level_to_existing_users_option() {
    return self::PLUGIN_PREFIX . '_add_level_to_existing_users';
  }

}
