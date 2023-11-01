<?php namespace Locale;

class Locale {

	private array $strings = [];

	public function __construct(string $defaultLang) {

		$lang = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
		$languages = array_merge([$defaultLang], array_reverse(explode(",", $lang)));

		foreach ($languages as $lang) {
			$this->load_lang($lang);
		}

	}

	private function load_lang(string $lang) {
		$path = "../lang/" . basename("$lang.json");
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