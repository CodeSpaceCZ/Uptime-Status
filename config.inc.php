<?php

$config = [

	/**
	 * Uptime Kuma backends
	 * - URL addresses of backends
	 */
	"backends" => [
		"http://uptime-kuma1.local:3001", // KUMA_ID = 0
		"http://uptime-kuma2.local:3001"  // KUMA_ID = 1
	],

	/**
	 * Status pages
	 * - all available pages and their backends
	 * - when passing an array, other backends are used as failovers
	 */
	"pages" => [
		"public" => 0, // first defined page is visible on the home page
		"second" => [0, 1] // other pages are displayed on their own subpages based on their slug (eg. /second)
	],

	/**
	 * Monitor options
	 * - the key must be in one of formats below (MON_ID = monitor ID, KUMA_ID = backend ID, PAGE = page)
	 * 	 - MON_ID
	 *   - KUMA_ID:MON_ID
	 *   - PAGE/MON_ID
	 *   - PAGE/KUMA_ID:MON_ID
	 * - available options are listed below
	 *   - heartbeats: boolean
	 */
	"monitor_options" => [
		"4" => ["heartbeats" => true],
		"second/1" => ["heartbeats" => true],
		"public/0:2" => ["heartbeats" => true]
	],

	/**
	 * Enable twig caching for better performance
	 */
	"enable_twig_cache" => true,

	/**
	 * Default language code
	 */
	"default_language" => "en",

	/**
	 * Timezone of displayed date
	 */
	"timezone" => "Etc/UTC"

];
