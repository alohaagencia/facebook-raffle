<?php

use Slim\App;
use Dotenv\Dotenv;
use Slim\Container;

$dotenv = new Dotenv(__DIR__.'/..');
$dotenv->load();

$files = glob(__DIR__.'/../config/*.php');

$config = [];

foreach ($files as $file) {
    $setting = pathinfo($file, PATHINFO_FILENAME);
    $options = require $file;
    $config[$setting] = $options;
}

$container = new Container($config);

$app = new App($container);

return $app;
