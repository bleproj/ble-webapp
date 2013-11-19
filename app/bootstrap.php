<?php

//Setup the app
$appOptions = array('mode' => 'development',
    'view' => new \Slim\Views\Twig(),
    'templates.path' => '/var/www/bleserver/app/views'
    );
$app = new \Slim\Slim($appOptions);
$app->setname('BLE');
$response = $app->response();

$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => false
    ));
});

$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => true,
        \Slim\Log::DEBUG,
    ));
});

//Setup Pusher instance
$app->pusher = new Pusher('YOUR-API-KEY', 'YOUR-API-SECRET', 'YOUR-APP-ID');

//Setup the logger
$app->log = $app->getLog();
$app->log->debug('App starting');

$app->log = $app->getLog();

$app->hook('slim.before.dispatch', function () use ($app) {

    $path = explode("/", $app->request()->getPathInfo());

    if($path[1] != 'login' and $path[1] != 'uploadtest'){
        if (!$app->getCookie('username')) {
            $app->redirect('/login');
        } else {
            if($path[1] != 'uploadtest'){
                $app->view()->setData('username', $app->getCookie('username'));
            }
        }
    }

    if (!$path[1]) {
        $path[1] = 'home';
    }

    if (count($path) >= 1) {
        $app->view()->setData('section', $path[1]);
    }
});
