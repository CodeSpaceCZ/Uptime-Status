<?php

require_once("../vendor/autoload.php");
require_once("../model/page.php");
require_once(__DIR__ . "/filters.php");
require_once(__DIR__ . "/locale.php");

class UptimeStatus {

	private array $data;

	public function load_data() {

		$urls = [
			UPTIME_KUMA_URL . "/api/status-page/" . UPTIME_KUMA_PAGE,
			UPTIME_KUMA_URL . "/api/status-page/heartbeat/" . UPTIME_KUMA_PAGE
		];
		$arr = $this->download($urls);
		$this->data = Page::convert($arr[0], $arr[1])->export();
	}

	private function download(array $urls): array {
		$chs = []; $data = [];
		$mh = curl_multi_init();
		foreach ($urls as $url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($mh, $ch);
			array_push($chs, $ch);
		}
		do {
			$status = curl_multi_exec($mh, $active);
			if ($active) curl_multi_select($mh);
		} while ($active && $status == CURLM_OK);
		foreach ($chs as $ch) {
			array_push($data, json_decode(curl_multi_getcontent($ch), true));
			curl_multi_remove_handle($mh, $ch);
		}
		curl_multi_close($mh);
		return $data;
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

		$locale = new \Locale\Locale(DEFAULT_LANGUAGE);
		$twig->addFilter($locale->t());

		$ext = $twig->getExtension(\Twig\Extension\CoreExtension::class);
		$ext->setDateFormat($locale->get("dateformat"));
		$ext->setTimezone(TIMEZONE);

		echo $twig->render('index.twig', $this->data);

	}

}