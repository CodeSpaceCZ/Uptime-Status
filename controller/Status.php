<?php namespace UptimeStatus;

use UptimeStatus\Model\Page;

class Status {

	readonly int $backendId;

	readonly string $slug;

	public ?Page $page = null;

	public function __construct(int $backend_id, string $slug) {
		$this->backendId = $backend_id;
		$this->slug = $slug;
	}

	public function getPage(): ?Page {
		$this->page = Page::get($this, $this->backendId, $this->slug);
		return $this->page;
	}

	public function display(): void {

		if ($this->page == null) return;
		$data = $this->page->export();

		$twigConfig = [];
		if (Config::get("enable_twig_cache")) $twigConfig["cache"] = "../cache/twig/";

		$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . "/view/");
		$twig = new \Twig\Environment($loader, $twigConfig);

		$twig->addFilter(Filters::globalStatus());
		$twig->addFilter(Filters::statusIcon());
		$twig->addFilter(Filters::statusColor());

		$locale = new Locale(Config::get("default_language"));
		$twig->addFilter($locale->translate());

		$ext = $twig->getExtension(\Twig\Extension\CoreExtension::class);
		$ext->setDateFormat($locale->get("dateformat"));
		$ext->setTimezone(Config::get("timezone"));

		echo $twig->render('index.twig', $data);

	}

}
