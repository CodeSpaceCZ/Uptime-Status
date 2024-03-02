<?php namespace UptimeStatus;

class Locale {

	private array $strings = [];

	public function __construct(string $defaultLang) {

		$this->load_lang($defaultLang);

		$lang = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];

		foreach (array_reverse(explode(",", $lang)) as $l) {
			$this->load_lang(substr($l, 0, 2));
		}

	}

	private function load_lang(string $lang) {
		$path = dirname(__DIR__) . "/lang/" . basename("$lang.json");
		if (is_file($path)) {
			$content = file_get_contents($path);
			if (!$content) return;
			$arr = json_decode($content, true);
			if ($arr) $this->strings = array_merge($this->strings, $arr);
		}
	}

	public function get(string $msg, ?array $data = []) {
		if (array_key_exists($msg, $this->strings)) {
			$msg = $this->strings[$msg];
			foreach ($data as $key => $value) {
				$msg = str_replace("{{{$key}}}", $value, $msg);
			}
		}
		return $msg;
	}

	public function t() {
		return new \Twig\TwigFilter('t', function ($msg, $data = []) {
			return $this->get($msg, $data);
		});
	}

}