<?php namespace Filters;

require_once("../vendor/autoload.php");

function globalstatus() {
	return new \Twig\TwigFilter('globalstatus', function (int $online, int $total) {
		if ($online == $total) return 1;
		elseif ($online == 0) return 0;
		else return 2;
	});
}

function globalstatustext() {
	return new \Twig\TwigFilter('globalstatustext', function (int $status) {
		$msgs = [
			"none",
			"all",
			"some"
		];
		return "header.title.$msgs[$status]";
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