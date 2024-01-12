<?php namespace UptimeStatus\Model;

use UptimeStatus\Status;

class Group {

	public string $name;
	public array $stats = [0, 0, 0, 0];
	public array $monitors = [];

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function add_monitor(Monitor $monitor) {
		array_push($this->monitors, $monitor);
		$status = $monitor->get_status();
		$this->stats[$status]++;
	}

	public function get_stats(): array {
		return $this->stats;
	}

	public static function convert(Status $s, array $oldGroup, array $heartbeat): Group {
		$group = new Group($oldGroup["name"]);
		foreach ($oldGroup["monitorList"] as $oldMonitor) {
			$group->add_monitor(Monitor::convert($s, $oldMonitor, $heartbeat));
		}
		return $group;
	}

}