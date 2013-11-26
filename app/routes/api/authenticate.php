<?php

// The home page
$app->post('/api/authenticate', function() use ($app)  {

    // Get data that was received
    $rawData = $app->request()->getBody();
    $loginCredentials = json_decode($rawData, true);
    $username = $loginCredentials['username'];
    $password = $loginCredentials['password'];

    $app->log->debug("Authenticate: Got username:". $loginCredentials['username'] ." pass:" . $loginCredentials['password']);

    if(isValidClientCredentials($username, $password)){
        $app->log->debug('Authenticate: Client credentials are valid');

        $user = Client::find('all', array('conditions' => array('username = ?', $username)));
        $user = $user[0];

        // Get access token
        $accessToken = AccessToken::find('all', array('conditions' => array('user_id = ?', $user->id)));
        $accessToken = $accessToken[0];

        if($accessToken->value){
            $app->log->debug("Authenticate: Client '". $username ."' has existing token " . $accessToken->value);
        } else {
            $app->log->debug("Authenticate: Client has no exsiting token, generating a new one.");
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