<?php

require_once("../vendor/autoload.php");
require_once(__DIR__ . "/filters.php");

class UptimeStatus {

	private $data;

	public function load_data() {
		$page = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/public"), true);
		$status = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/heartbeat/public"), true);
		$this->data = array_merge($page, $status);
	}

	public function display() {
		
		$twig_config = [];
		if(ENABLE_TWIG_CACHE) $twig_config["cache"] = "../cache/twig/";
		
		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);
		$twig->addFilter(\Filters\timeago());
		
		$monitoring = MONITORINGS ?? [];

		echo $twig->render('index.twig', array_merge(["monitoring" => $monitoring], $this->data));

	}

}