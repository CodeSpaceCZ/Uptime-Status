<?php

require("../config.inc.php");
require("../controller/status.php");

$status = new UptimeStatus;

$status->load_data();
$status->display();
