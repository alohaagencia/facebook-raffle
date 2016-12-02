<?php

use Facebook\Facebook;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Version
$app->get('/', function (Request $request, Response $response) {
    $message = sprintf('Welcome to %s %s', $this->get('app')['name'], $this->get('app')['version']);

    return $response->withStatus(200)->write($message);
});

// Users
$app->post('/api/users', function (Request $request, Response $response) {
    $post = $request->getParsedBody();

    if (! array_key_exists('link', $post) || ! filter_var($post['link'], FILTER_VALIDATE_URL)) {
        return $response->withStatus(422)->withJson([
            'error' => 'Invalid facebook post link.',
        ]);
    }

    $url = $post['link'];

    preg_match('/(\/[0-9]+\/)/', $url, $matches);

    $postId = preg_replace('/[^0-9]/', null, $matches[0]);

    try {
        $facebook = new Facebook([
            'app_id' => $this->get('services')['facebook']['app_id'],
            'app_secret' => $this->get('services')['facebook']['app_secret'],
            'default_graph_version' => 'v2.5',
            'default_access_token' => $this->get('services')['facebook']['app_default_access_token'],
        ]);

        $facebookResponse = $facebook->get(sprintf('%s/comments?limit=100', $postId));

        foreach ($facebookResponse->getGraphEdge() as $item) {
            $from = $item->asArray()['from'];

            $users[] = [
                'name' => $from['name'],
                'link' => '//facebook.com/'.$from['id'],
            ];
        }

        return $response->withStatus(200)->withJson([
            'data' => $users,
        ]);
    } catch (Exception $e) {
        return $response->withStatus(500)->withJson([
            'error' => $e->getMessage(),
        ]);
    }
});
