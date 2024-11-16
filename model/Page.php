<?php namespace UptimeStatus\Model;

use UptimeStatus\Config;
use UptimeStatus\Status;

class Page {

	private array $page;
	private array $stats = [0, 0, 0, 0];
	private int $total = 0;
	private array $groups = [];

	public function __construct(array $page) {
		$this->page = $page;
	}

	public function add_group(Group $group) {
		array_push($this->groups, $group);
		$stats = $group->get_stats();
		for ($i = 0; $i < count($this->stats); $i++) {
			$count = $stats[$i];
			$this->stats[$i] += $count;
			$this->total += $count;
		}
	}

	public function export(): array {
		return [
			"nav" => Config::get("nav"),
			"page" => $this->page,
			"stats" => $this->stats,
			"total" => $this->total,
			"groups" => $this->groups
		];
	}

	public static function get(Status $s, int $backend_id, string $page): ?Page {

		$backend = Config::get("backends")[$backend_id];
		$urls = [
			$backend . "/api/status-page/" . $page,
			$backend . "/api/status-page/heartbeat/" . $page
		];

		[$oldPage, $heartbeat] = Page::download($urls);
		if (!is_array($oldPage) || !is_array($heartbeat)) {
			return null;
		}

		$page = new Page($oldPage["config"]);
		foreach ($oldPage["publicGroupList"] as $oldGroup) {
			$page->add_group(Group::convert($s, $oldGroup, $heartbeat));
		}
		return $page;

	}

	public static function download(array $urls): array {
		$chs = []; $data = [];
		$mh = curl_multi_init();
		foreach ($urls as $url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
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

}