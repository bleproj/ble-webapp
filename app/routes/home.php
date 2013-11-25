<?php

// The home page
$app->get('/', function() use ($app)  {

    $data['users'] = User::all();
    $data['clients'] = Client::all();
    $data['tokens'] = AccessToken::all();
    $data['theToken'] = generate_access_token();

    $app->render('home.twig', $data);
});