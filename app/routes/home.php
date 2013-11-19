<?php

// The home page
$app->get('/', function() use ($app)  {


    /* Example of getting data and passing it to the view

    $data['testtypes'] = TestType::find('all');
    $data['latesttests'] = Test::find('all', array('order' => 'id desc', 'limit' => 5));
    $data['stats']['tests']['title'] = 'Total number of tests: ';
    $data['stats']['tests']['value']= Test::count();
    $data['stats']['events']['title'] = 'Total number of events: ';
    $data['stats']['events']['value']= Event::count();
    $data['clients'] = Client::all(); */

    $data = array();

    $app->render('home.twig', $data);
});