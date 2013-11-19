<?php
/**
 * Sets up error catching and runs the app.
 */

$app->error(function ( Exception $e ) use ($app) {
    if(get_class($e) == 'PDOException'){
        $payload = array('error' => array('message' => 'Database error: ' . $e->errorInfo[2], 'code' => 3));
    } else {
        $payload = array('error' => array('message' => $e->getMessage(). ' in file ' . basename($e->getFile()) . ' on line ' . $e->getLine(), 'code' => $e->getCode()));
    }

    if(get_class($e) == 'JSONException'){
        $app->response()->header('Content-Type', 'application/json');
        rest_output($payload);
    } else {
        $app->render('error.twig', $payload);
    }

});

// Error page setup
$app->notFound(function () use ($app) {
    $app->render('error.twig', array('error' => array('code' => 404, 'message' => 'Page not found.')));
});

//Run that mofo!
$app->run();