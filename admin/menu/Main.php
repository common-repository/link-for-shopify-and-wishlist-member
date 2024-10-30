<?php
namespace link_for_shopify_and_wishlist_member\admin\menu;

use link_for_shopify_and_wishlist_member\admin\UserOptions;
use link_for_shopify_and_wishlist_member\Settings;

class Main {

  const MENU_SLUG = 'link_for_shopify_and_wishlist_member-main';

  public static function addMenu() {
    $pageTitle = 'Link for Shopify and WishlistMember';
    $menuTitle = 'Link for Shopify and WishlistMember';
    $capability = 'manage_options';
    $menuSlug = self::MENU_SLUG;
    $function = array('link_for_shopify_and_wishlist_member\admin\menu\Main', 'showMenu');
    $iconUrl = ''; // TODO: add icon url
    $position = '99.363318'; // wlm is 99.363317
    add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, $function, $iconUrl, $position);
  }

  public static function showMenu() { // uncovered
    if (current_user_can('read') === false) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $query = http_build_query(array('action' => Settings::USER_OPTIONS_ACTION));
    $url = admin_url('admin-post.php?' . $query);
    $user_options = new UserOptions();
    $auto_add_checkbox = $user_options->get_add_level_to_existing_users() ? "checked='checked'" : '';

    echo "
<style>
  #link-for-shopify-and-wlm-settings button{
    display: block;
  }
</style>
<div class='wrap'>
  <form id='link-for-shopify-and-wlm-settings' action='$url' method='post'>
    <label for='auto_add'>Automatically login and add levels to existing users</label>
    <input id='auto_add' name='auto_add' type='checkbox' $auto_add_checkbox />
    <button type='submit'>Save</button>
  </form>
</div>";
  }

  public static function updateSettings() {
    $user_options = new UserOptions();
    // TODO: constantize autoadd?
    $user_options->set_add_level_to_existing_users(array_key_exists('auto_add', $_REQUEST));
    $url = admin_url(sprintf('admin.php?page=%s', self::MENU_SLUG));
    wp_redirect($url);
  }
}
