<?php

$logWriter = new \Slim\LogWriter(fopen('/var/www/bleserver/app/log/log.txt', 'a'));

//Setup the app
$appOptions = array('mode' => 'development',
    'view' => new \Slim\Views\Twig(),
    'templates.path' => '/var/www/bleserver/app/views',
    'log.writer' => $logWriter
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
        'debug' => false
    ));
});

//Setup Pusher instance
$app->pusher = new Pusher('c1f79d5768d5b59431e5', 'bd8a585c5196e503f634', '60313');

//Setup logging
$app->log = $app->getLog();
$app->log->setLevel(\Slim\Log::DEBUG);

//$log->setEnabled(true);

$app->hook('slim.before', function () use ($app) {

    // Only enable logging for API requests
    $path = substr($app->request()->getPathInfo(),1);
    $dirArr = explode('/',$path);
    $rootDir = $dirArr[0];

    if($rootDir != 'api'){
      $app->log->setEnabled(false);
    }
    $app->log->info('App started:');
});

$app->hook('slim.before.dispatch', function () use ($app) {
    $app->log->info($app->request->getMethod() . ' request to ' . $app->request()->getPathInfo() . ' ');
});

$app->hook('slim.after', function () use ($app) {
    $app->log->info("App finished\n");
    $app->pusher->trigger('utils', 'log-updated', '');
});