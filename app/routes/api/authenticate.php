<?php

// The home page
$app->post('/api/authenticate', function() use ($app)  {

    // Get data that was received
    $rawData = $app->request()->getBody();
    $loginCredentials = json_decode($rawData, true);
    $username = $loginCredentials['username'];
    $password = $loginCredentials['password'];

    $app->log->debug("Authenticate: Got username:". $loginCredentials['username'] ." pass:" . $loginCredentials['password']);
    $app->log->debug("Got request body: " . $app->request()->getBody());

    if(isValidClientCredentials($username, $password)){
        $app->log->debug('Authenticate: Client credentials are valid');

        $client = Client::find('all', array('conditions' => array('username = ?', $username)));
        $client = $client[0];

        // Get access token
        $accessToken = Accesstoken::find('all', array('conditions' => array('clientid = ?', $client->id)));

        if(isset($accessToken[0]) && $accessToken[0]->value){
            $accessToken = $accessToken[0];
            $app->log->debug("Authenticate: Client '". $username ."' has existing token " . $accessToken->value);
        } else {
            $app->log->debug("Authenticate: Client has no exsiting token, generating a new one.");
            $accessToken = new Accesstoken();
            $accessToken->value = generate_access_token();
            $accessToken->clientid = $client->id;
            $accessToken->save();
        }

        // Construct response
        $response['message'] = "Valid credentials";
        $response['access_token'] = $accessToken->value;
        $response['code'] = 200;
        rest_output($response);

    } else {
        throw new JSONException('The credentials received did not match any client', 403);
    }
});