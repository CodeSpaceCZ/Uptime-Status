<?php

require_once(__DIR__ . "/monitor.php");

class Group {

	private string $name;
	private int $online = 0;
	private array $monitors = [];

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

	public function export(): array {
		$monitors = [];
		foreach($this->monitors as $monitor) {
			array_push($monitors, $monitor->export());
		}
		return [
			"name" => $this->name,
			"online" => $this->online,
			"monitors" => $monitors
		];
	}

	public static function convert(array $oldGroup, array $heartbeat): Group {
		$group = new Group($oldGroup["name"]);
		foreach ($oldGroup["monitorList"] as $oldMonitor) {
			$group->add_monitor(Monitor::convert($oldMonitor, $heartbeat));
		}
		return $group;
	}

}