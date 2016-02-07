<?php
session_cache_limiter(false);
session_start();
require 'db.php';
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

//For application/json
$body = json_decode($app->request->getBody());

/* ROUTING */
require "routes/settings.php";
$app->group('/settings', function () use ($app) {
    $app->get('','getSettings');
    $app->get('/:value','getSetting');
});

require "routes/users.php";
$app->group('/users', function () use ($app) {
    $app->get('','getUsers');
    $app->get('/search/:query','getUserSearch');
    $app->post('/login', 'login');
    $app->get('/logout', 'logout');
    $app->get('/isLoggedIn', 'isLoggedIn');
    $app->post('/register', 'register');
});

require "routes/updates.php";
$app->group('/updates', function () use ($app) {
    $app->get('','getUserUpdates');
    $app->post('', 'insertUpdate');
    $app->delete('/:update_id','deleteUpdate');
});

/* Slim Framework settings */
$app->contentType('application/json');
//make sure browsers don't cache the json content
$app->expires('-1 hour');
$app->notFound(function () {
    echo '{"error":{"message":"404 Page Not Found"}}';
});

$app->run();

function getBody() {
    global $body;
    return $body;
}