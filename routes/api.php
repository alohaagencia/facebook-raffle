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

        $users = getRecursiveResults(sprintf('%s/likes?limit=500&fields=id,name,pic_large', $postId), $facebook);

        return $response->withStatus(200)->withJson([
            'total' => count($users),
            'data' => $users,
        ]);
    } catch (Exception $e) {
        return $response->withStatus(500)->withJson([
            'error' => $e->getMessage(),
        ]);
    }
});

function getRecursiveResults($url, $facebook, $results = [])
{
    $facebookResponse = $facebook->get($url);
    $facebookResults = $facebookResponse->getGraphEdge();

    foreach ($facebookResults as $item) {
        $user = $item->asArray();

        $results[] = [
            'name' => $user['name'],
            'profile' => sprintf('https://facebook.com/%s', $user['id']),
            'profile_picture' => $user['pic_large'],
        ];
    }

    if ($facebookResults->getNextPageRequest()) {
        return getRecursiveResults($facebookResults->getNextPageRequest()->getEndpoint(), $facebook, $results);
    }

    return $results;
}
