<?php

require_once(__DIR__ . "/group.php");

class Page {

	private array $page;
	private int $online = 0;
	private int $total = 0;
	private array $groups = [];
	private string $date_format;
	private string $timezone;

	public function __construct(array $page) {
		$this->page = $page;
		$this->date_format = DATE_FORMAT;
		$this->timezone = TIMEZONE;
	}

	public function add_group(Group $group) {
		array_push($this->groups, $group);
		$this->online += $group->get_online();
		$this->total += $group->get_total();
	}

	public function export(): array {
		$groups = [];
		foreach($this->groups as $group) {
			array_push($groups, $group->export());
		}
		return [
			"page" => $this->page,
			"online" => $this->online,
			"total" => $this->total,
			"groups" => $groups,
			"date_format" => $this->date_format,
			"timezone" => $this->timezone
		];
	}

	public static function convert(array $oldPage, array $heartbeat): Page {
		$page = new Page($oldPage["config"]);
		foreach ($oldPage["publicGroupList"] as $oldGroup) {
			$page->add_group(Group::convert($oldGroup, $heartbeat));
		}
		return $page;
	}

}