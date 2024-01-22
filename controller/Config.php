<?php namespace UptimeStatus;

use Exception;

class Config {

	private static array $config;

	public static function set(array $config): void {
		self::$config = $config;
	}

	public static function get(string $key, mixed $default = null): mixed {
		if (array_key_exists($key, self::$config)) {
			return self::$config[$key];
		}
		if ($default !== null) {
			return $default;
		}
		throw new Exception("Missing config key '$key'!");
	}

}