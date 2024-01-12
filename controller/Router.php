<?php namespace UptimeStatus;

class Router {

	private Status $status;

	public function __construct($config) {
		$this->status = new Status($config);
	}

	public function get_path(): string {
		$url = $_SERVER["REQUEST_URI"];
		$parsed_url = parse_url($url);
		$path = "";
		if (isset($parsed_url["path"])) {
			$path = substr($parsed_url["path"], 1);
		}
		if ($path == "" && isset($_GET["q"])) {
			$path = "{$_GET["q"]}";
		}
		return $path;
	}

	public function get_page($path) {
		$pages = $this->status->cfg("pages");
		foreach ($pages as $page) {
			if ($path == $page) {
				return $page;
			}
		}
		return $pages[0];
	}

	public function render() {
	
		$path = $this->get_path();
		$slug = $this->get_page($path);

		$page = $this->status->get_page($slug);
		if (!$page) die("Failed to load data!");
		
		$this->status->display($page);

	}

}