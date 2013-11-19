<?php
// Listing of tests
$app->get('/tests/', function() use ($app)  {

    $typeFilter = $app->request()->get('type');
    $data['testtypes'] = TestType::find('all');

    if($typeFilter and isValidTestType($typeFilter)){
        $data['tests'] = Test::find_all_by_type($typeFilter, array('order' => 'id desc'));
        $data['testtype'] = TestType::find_by_name($typeFilter);
        $data['typeFilter'] = $typeFilter;
        $data['typeFilterReduced'] = str_replace('Test', '', $typeFilter);
    } else {
        $data['tests'] = Test::find('all', array('order' => 'id desc'));
    }

    $app->render('tests.twig', $data);
});

// View a specific test
$app->get('/tests/:id/', function($id) use ($app)  {

    // Delete test
    if($app->request()->params('delete')){
        Event::table()->delete(array('test_id' => $id));
        Test::table()->delete(array('id' => $id));
        $app->redirect('/tests/');
    }

    // View test
    else {
        $test = Test::find($id)->getSpecificTestTypeObject();
        $app->render( $test->getViewFilename(), $test->getViewData($app->request()->params('compareTo')));
    }
});

$app->get('/tests/:id/:graph/', function($id, $graph) use ($app)  {
    $test = Test::find($id)->getSpecificTestTypeObject();
    rest_output($test->getGraphData($graph));
});

$app->get('/tests/:id/:graph/:compareto', function($id, $graph, $compareTo) use ($app)  {
    $test = Test::find($id)->getSpecificTestTypeObject();
    rest_output($test->getGraphData($graph, $compareTo));
});

