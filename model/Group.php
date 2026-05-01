<?php namespace UptimeStatus\Model;

use UptimeStatus\Status;

class Group {

	public string $name;
	public array $stats = [0, 0, 0, 0];
	public array $monitors = [];

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function addMonitor(Monitor $monitor) {
		array_push($this->monitors, $monitor);
		$status = $monitor->getStatus();
		$this->stats[$status]++;
	}

	public function getStats(): array {
		return $this->stats;
	}

	public static function convert(Status $s, array $oldGroup, array $heartbeat): Group {
		$group = new Group($oldGroup["name"]);
		foreach ($oldGroup["monitorList"] as $oldMonitor) {
			$group->addMonitor(Monitor::convert($s, $oldMonitor, $heartbeat));
		}
		return $group;
	}

}
