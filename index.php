<?php
/*
Plugin Name: Link for Shopify and Wishlist Member
Plugin URI:  http://www.shopifywishlistmember.com
Description: Connects Shopify to Wishlist Member for use with the Shopify Link for Shopify and WishList Member App
Version:     1.0.4
Author:      Kenton Hirowatari
License:     MS_RSL
License URI: http://referencesource.microsoft.com/license.html
*/
// should be 'require' but in testing the WordPressCaller loads this plugin.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoloader.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'http_response_code_compat.php';

// TODO: refactor into Loader class
// TODO: refactor ridiculous base namespace
add_filter('do_parse_request', 'swc_do_parse_request', 10, 3);
function swc_do_parse_request($continue, $wp, $extra_query_vars)
{
  $request_handler = new \link_for_shopify_and_wishlist_member\RequestHandler();
  $response = $request_handler->dispatch_request($_REQUEST);
  if ($response['continue']) {
    return $continue;
  }
  else {
    if(array_key_exists('output', $response)) {
      echo $response['output'];
    }
    // TODO: add error output function
    die();
  }
}
add_action('admin_menu', array('\link_for_shopify_and_wishlist_member\admin\menu\Main', 'addMenu'));

add_action('admin_post_' . \link_for_shopify_and_wishlist_member\Settings::USER_OPTIONS_ACTION,
  array('\link_for_shopify_and_wishlist_member\admin\menu\Main', 'updateSettings'));

add_action('set_logged_in_cookie',
  array('\link_for_shopify_and_wishlist_member\register\TokenUsage', 'recordTokenUsed'), 10, 5);
