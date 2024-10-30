<?php
namespace link_for_shopify_and_wishlist_member;

class Config
{
  public static function SHOPIFY_APP_URL()
  {
    if (getenv('SWC_DEVELOPMENT') == 'true') {
      return 'web:3000';
    }
    else {
      return 'https://www.shopifywishlistmember.com';
    }
  }
}