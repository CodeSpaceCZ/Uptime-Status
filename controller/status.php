<?php

require_once("../vendor/autoload.php");
require_once("../model/group.php");
require_once(__DIR__ . "/filters.php");

class UptimeStatus {

	private $data;

	public function load_data() {
		$page = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/public"), true);
		$heartbeat = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/heartbeat/public"), true);
		$groups = [];
		foreach ($page["publicGroupList"] as $oldGroup) {
			array_push($groups, Group::convert($oldGroup, $heartbeat)->export());
		}
		$this->data = ["page" => $page["config"], "groups" => $groups];
	}

	public function raw() {
		return $this->data;
	}

	public function display() {
		
		$twig_config = [];
		if (ENABLE_TWIG_CACHE) $twig_config["cache"] = "../cache/twig/";
		
		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);
		$twig->addFilter(\Filters\timeago());

		echo $twig->render('index.twig', array_merge($this->data));

	}

}