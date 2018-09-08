<?php

define('APP_ROOT'. '/');

require_once 'helper/Bootstrap.php';

use lib\App;

$app = new App();
$app->run();