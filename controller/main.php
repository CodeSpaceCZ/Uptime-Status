<?php

require("../config.inc.php");
require(__DIR__ . "/router.php");

$router = new Router($config);

$router->render();