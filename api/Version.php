<?php
namespace link_for_shopify_and_wishlist_member\api;

class Version {
	const VERSION = '0.1.1';

	public function get_version() {
		return \json_encode( array( 'version' => self::VERSION ) );
	}
}