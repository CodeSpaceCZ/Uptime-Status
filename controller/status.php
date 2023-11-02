<?php

require_once("../vendor/autoload.php");
require_once("../model/page.php");
require_once(__DIR__ . "/filters.php");
require_once(__DIR__ . "/locale.php");

class UptimeStatus {

	private array $config;

	public function __construct(array $config) {
		$this->config = $config;
	}

	public function cfg($name) {
		if(array_key_exists($name, $this->config)) {
			return $this->config[$name];
		}
		return null;
	}

	public function get_page(string $page): ?Page {
		return Page::get($this, $page);
	}

	public function display(Page $page) {

		$data = $page->export();

		$twig_config = [];
		if ($this->cfg("enable_twig_cache")) $twig_config["cache"] = "../cache/twig/";

		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);

		$twig->addFilter(\Filters\globalstatus());
		$twig->addFilter(\Filters\globalstatustext());
		$twig->addFilter(\Filters\statusicon());

		$locale = new \Locale\Locale($this->cfg("default_language"));
		$twig->addFilter($locale->t());

		$ext = $twig->getExtension(\Twig\Extension\CoreExtension::class);
		$ext->setDateFormat($locale->get("dateformat"));
		$ext->setTimezone($this->cfg("timezone"));

		echo $twig->render('index.twig', $data);

	}

}