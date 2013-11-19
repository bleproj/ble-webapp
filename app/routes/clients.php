<?php

// Listing of clients
$app->get('/clients/', function() use ($app)  {
        $data['clients'] = Client::find('all');
        $data['accesstokens'] = AccessToken::find('all');
        $app->render('clients.twig', $data);
});

// Create a new client
$app->post('/clients/', function() use ($app)  {
    if($app->request()->params('client_name')){
        $client = new Client();
        $client->name = $app->request()->params('client_name');
        $client->description = $app->request()->params('client_description');
        $client->save();
        $app->redirect('/clients/');
    }
});
