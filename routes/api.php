<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Version
$app->get('/', function (Request $request, Response $response) {
    return $response->withStatus(200)->write('Welcome to Facebook Raffle v0.1.0');
});

// Users
$app->post('/api/users', function (Request $request, Response $respose) {
    echo '<pre>', print_r($request, true), '</pre>';
});
