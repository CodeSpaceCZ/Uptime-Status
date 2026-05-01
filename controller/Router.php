<?php namespace UptimeStatus;

class Router {

	public function getPath(): string {
		$url = $_SERVER["REQUEST_URI"];
		$parsedUrl = parse_url($url);
		$path = "";
		if (isset($parsedUrl["path"])) {
			$path = substr($parsedUrl["path"], 1);
		}
		if ($path == "" && isset($_GET["q"])) {
			$path = "{$_GET["q"]}";
		}
		return $path;
	}

	public function getPage($path): string {
		$pages = Config::get("pages");
		$slugs = array_keys($pages);
		foreach ($slugs as $page) {
			if ($path == $page) {
				return $page;
			}
		}
		return $slugs[0];
	}

	public function render() {

		$path = $this->getPath();
		$slug = $this->getPage($path);

		$backendIds = Config::get("pages")[$slug];

		if (!is_array($backendIds)) $backendIds = [$backendIds];

		$status = null;
		foreach ($backendIds as $bid) {
			$status = new Status($bid, $slug);
			$page = $status->getPage();
			if ($page) {
				$status->display();
				return;
			}
		}
		echo "Failed to load data!";

	}

}
