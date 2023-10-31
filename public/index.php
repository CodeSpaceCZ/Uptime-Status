<?php

require_once("../config.inc.php");
require_once("../vendor/autoload.php");

$loader = new \Twig\Loader\FilesystemLoader("../view/");
$twig = new \Twig\Environment($loader, [
    'cache' => '../cache/twig/',
]);

$page = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/public"), true);
$monitors = json_decode(file_get_contents(UPTIME_KUMA_URL . "/api/status-page/heartbeat/public"), true);

$data = array_merge($page, $monitors);

echo $twig->render('index.twig', $data);