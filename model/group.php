<?php

require_once(__DIR__ . "/monitor.php");

class Group {

	public string $name;
	public int $online = 0;
	public array $monitors = [];

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function add_monitor(Monitor $monitor) {
		array_push($this->monitors, $monitor);
		if($monitor->is_online()) $this->online++;
	}

	public function get_online(): int {
		return $this->online;
	}

	public function get_total(): int {
		return count($this->monitors);
	}

	public static function convert(array $oldGroup, array $heartbeat): Group {
		$group = new Group($oldGroup["name"]);
		foreach ($oldGroup["monitorList"] as $oldMonitor) {
			$group->add_monitor(Monitor::convert($oldMonitor, $heartbeat));
		}
		return $group;
	}

}