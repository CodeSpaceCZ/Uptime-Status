<?php namespace UptimeStatus\Model;

use UptimeStatus\Status;

class Monitor {

	public string $name;
	public float $uptime;
	public array $last;
	public array $heartbeats;
	public array $opt;

	public function __construct(string $name, float $uptime, array $heartbeats, array $opt = []) {
		$this->name = $name;
		$this->uptime = $uptime;
		$this->last = $heartbeats[count($heartbeats) - 1];
		$this->heartbeats = $heartbeats;
		$this->opt = $opt;
	}

	public function get_status() {
		return $this->last["status"] ?? 0;
	}

	public static function convert(Status $s, array $oldMonitor, array $heartbeat): Monitor {

		$id = $oldMonitor["id"];
		$opts = $s->cfg("monitor_options") ?? [];
		$opt = $opts[$id] ?? [];

		$heartbeats = [];
		foreach ($heartbeat["heartbeatList"][$id] as $beat) {
			$beat["time"] = strtotime($beat["time"]);
			array_push($heartbeats, $beat);
		}

		return new Monitor(
			$oldMonitor["name"],
			$heartbeat["uptimeList"][$id . "_24"],
			$heartbeats,
			$opt
		);
	}

}