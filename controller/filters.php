<?php namespace Filters;

require_once("../vendor/autoload.php");

function globalstatus() {
	return new \Twig\TwigFilter('globalstatus', function (array $stats, int $total) {
		if ($stats[3] > 0) return 3;
		if ($stats[1] == $total) return 1;
		if ($stats[1] == 0) return 0;
		return 2;
	});
}

function statusicon() {
	return new \Twig\TwigFilter('statusicon', function (int $status) {
		$icons = [
			0 => "error",
			1 => "success",
			2 => "warning",
			3 => "maintenance"
		];
		return "/icon/{$icons[$status]}.svg";
	});
}