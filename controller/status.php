<?php

require_once("../vendor/autoload.php");

class UptimeStatus {

	private $data;

	public function load_data() {
		$page = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/public"), true);
		$status = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/heartbeat/public"), true);
		$this->data = array_merge($page, $status);
	}

	public function display() {

		$filter = new \Twig\TwigFilter('timeago', function ($ago, $now) {

			$time = strtotime($now) - strtotime($ago); 
		  
			$units = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);
		  
			foreach ($units as $unit => $val) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return ($val == 'second')? 'a few seconds ago' : 
					(($numberOfUnits>1) ? $numberOfUnits : 'a')
					.' '.$val.(($numberOfUnits>1) ? 's' : '').' ago';
			}
		  
		});
		
		$twig_config = [];
		if(ENABLE_TWIG_CACHE) $twig_config["cache"] = "../cache/twig/";
		
		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);
		$twig->addFilter($filter);
		
		$monitoring = MONITORINGS ?? [];

		echo $twig->render('index.twig', array_merge(["monitoring" => $monitoring], $this->data));

	}

}