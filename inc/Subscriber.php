<?php
declare( strict_types=1 );

namespace WPMedia\PackageTemplate;

use WPMedia\EventManager\SubscriberInterface;

class Subscriber implements SubscriberInterface {
	/**
	 * Returns the list of subscribed events.
	 *
	 * @return array The list of subscribed events.
	 */
	public static function get_subscribed_events(): array {
		return [];
	}
}
