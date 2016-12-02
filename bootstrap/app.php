<?php

use Slim\App;

$config = require __DIR__.'/../config/app.php';

$app = new App($config);

return $app;
