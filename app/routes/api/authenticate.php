<?php

// The home page
$app->post('/api/authenticate', function() use ($app)  {

    rest_output(print_r($app->request(), false));

    //$app->render('home.twig', $data);
});