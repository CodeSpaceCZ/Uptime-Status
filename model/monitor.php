<?php

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

	public function is_online() {
		return $this->last["status"] == 1;
	}

	public static function convert(UptimeStatus $s, array $oldMonitor, array $heartbeat): Monitor {

		$id = $oldMonitor["id"];
		$opts = $s->cfg("monitor_options") ?? [];
		$opt = $opts[$id] ?? [];

		return new Monitor(
			$oldMonitor["name"],
			$heartbeat["uptimeList"][$id . "_24"],
			$heartbeat["heartbeatList"][$id],
			$opt
		);
	}

}