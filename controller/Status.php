<?php namespace UptimeStatus;

use UptimeStatus\Model\Page;

class Status {

	readonly int $backend_id;

	readonly string $slug;

	public ?Page $page = null;

	public function __construct(int $backend_id, string $slug) {
		$this->backend_id = $backend_id;
		$this->slug = $slug;
	}

	public function get_page(): ?Page {
		$this->page = Page::get($this, $this->backend_id, $this->slug);
		return $this->page;
	}

	public function display(): void {

		if ($this->page == null) return;
		$data = $this->page->export();

		$twig_config = [];
		if (Config::get("enable_twig_cache")) $twig_config["cache"] = "../cache/twig/";

		$loader = new \Twig\Loader\FilesystemLoader("../view/");
		$twig = new \Twig\Environment($loader, $twig_config);

		$twig->addFilter(Filters::globalstatus());
		$twig->addFilter(Filters::statusicon());
		$twig->addFilter(Filters::statuscolor());

		$locale = new Locale(Config::get("default_language"));
		$twig->addFilter($locale->t());

		$ext = $twig->getExtension(\Twig\Extension\CoreExtension::class);
		$ext->setDateFormat($locale->get("dateformat"));
		$ext->setTimezone(Config::get("timezone"));

		echo $twig->render('index.twig', $data);

	}

}