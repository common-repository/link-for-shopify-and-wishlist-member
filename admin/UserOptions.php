<?php
namespace link_for_shopify_and_wishlist_member\admin;

use \link_for_shopify_and_wishlist_member\Settings;

class UserOptions {

  // get_options() doesn't seem to reliably return a consistent type.
  // Only store strings there since otherwise
  // they may or may not get unserialized.
  public function set_add_level_to_existing_users($value) {
    $setting_name = Settings::add_level_to_existing_users_option();
    if ($value) {
      update_option($setting_name, 'true');
    } else {
      update_option($setting_name, 'false');
    }
  }

  public function get_add_level_to_existing_users() {
    $setting_name = Settings::add_level_to_existing_users_option();
    return get_option($setting_name, 'false') === 'true' ? true : false;
  }

}
