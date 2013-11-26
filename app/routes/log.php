<?php

// The home page
$app->get('/log', function() use ($app)  {

    $data['section'] = 'log';
    $app->render('log.twig', $data);
});