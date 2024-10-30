<?php
spl_autoload_register('link_for_shopify_and_wishlist_member_autoloader');
function link_for_shopify_and_wishlist_member_autoloader($class)
{
  $prefix = 'link_for_shopify_and_wishlist_member\\';
  $base_dir = __DIR__ . DIRECTORY_SEPARATOR;

  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    return;
  }
  $relative_class = substr($class, $len);
  $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
  if (file_exists($file)) {
    require $file;
  }
}
