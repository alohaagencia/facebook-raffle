<?php

use Slim\App;
use Slim\Container;

$config = require __DIR__.'/../config/app.php';

$container = new Container($config);

$app = new App($container);

return $app;
