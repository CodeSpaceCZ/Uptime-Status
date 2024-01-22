<?php

use UptimeStatus\Config;
use UptimeStatus\Router;

require("../vendor/autoload.php");
require("../config.inc.php");

Config::set($config);
$router = new Router();
$router->render();