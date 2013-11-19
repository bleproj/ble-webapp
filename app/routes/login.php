<?php

// Login page
$app->get('/login', function() use ($app)  {

    // Logout routine
    if($app->request()->params('logout')){
        $app->deleteCookie('username');
        $app->redirect('/login');
    }

    $data = [];

    // Wrong username / password
   if($app->request()->params('wrong')){
       $data['wrong'] = 'yes';
   };

   $app->render('login.twig', $data);
});

// Handle login form post
$app->post('/login', function() use ($app)  {
    $username = $app->request()->params('username');
    $password = $app->request()->params('password');

    if(isValidLoginCredentials($username, $password)){
        $app->setCookie('username', $username, '30 days');
        $app->redirect('/');
    } else {
        $app->redirect('/login?wrong=yep');
    }
});