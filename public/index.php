<?php

require("../vendor/autoload.php");
require("../config.inc.php");

$router = new UptimeStatus\Router($config);

$router->render();