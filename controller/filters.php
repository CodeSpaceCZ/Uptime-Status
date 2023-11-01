<?php namespace Filters;

require_once("../vendor/autoload.php");

function timediffmin() {
	return new \Twig\TwigFilter('timediffmin', function ($ago, $now) {
		return round((strtotime($now) - strtotime($ago)) / 60);
	});	
}

function isof() {
	return new \Twig\TwigFilter('isof', function ($online, $total) {
		if($online == $total) return "all";
		elseif($online == 0) return "none";
		else return "some";
	});
}