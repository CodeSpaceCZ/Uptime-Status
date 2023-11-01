<?php

require_once("../vendor/autoload.php");
require_once("../model/page.php");
require_once(__DIR__ . "/filters.php");

class UptimeStatus {

	private $data;

	public function load_data() {
		$page = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/" . UPTIME_KUMA_PAGE), true);
		$heartbeat = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/heartbeat/" . UPTIME_KUMA_PAGE), true);
		$this->data = Page::convert($page, $heartbeat)->export();
	}

	public function raw() {
		return $this->data;
	}

	public function display() {
		
		$twig_config = [];
		if (ENABLE_TWIG_CACHE) $twig_config["cache"] = "../cache/twig/";
		
		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);
		$twig->addFilter(\Filters\timediffmin());
		$twig->addFilter(\Filters\isof());

		echo $twig->render('index.twig', $this->data);

	}

}