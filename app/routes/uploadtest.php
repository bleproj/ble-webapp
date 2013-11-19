<?php

$app->post('/uploadtest', function () use ($app) {

    $app->response()->header('Content-Type', 'application/json');

    // Authenticate sender
    $access_token = $app->request()->params('access_token');

    try {
        AccessToken::find_by_pk(array($access_token), array('conditions' => 'valid_before > NOW()'));
    } catch (Exception $e) {
        throw new JSONException('Invalid access token suppied, access denied.', 403);
    }

    // Get data that was received
    $rawData = $app->request()->getBody();
    //$rawData = '{"events": [{"eventType": "TransferStartEvent", "data": {"accessPointName": "no wifi"}, "timeStamp": "2013-03-04 10:49:57:890"}, {"eventType": "AccessPointChange", "data": {"newAccessPointName": "no wifi"}, "timeStamp": "2013-03-04 10:49:57:896"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 10000000, "goodput": 10000000}, "timeStamp": "2013-03-04 10:49:57:896"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 315392, "goodput": -9684608}, "timeStamp": "2013-03-04 10:49:58:080"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 1445888, "goodput": 1130496}, "timeStamp": "2013-03-04 10:49:58:211"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 2707456, "goodput": 1261568}, "timeStamp": "2013-03-04 10:49:58:343"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 3940352, "goodput": 1232896}, "timeStamp": "2013-03-04 10:49:58:467"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 5201920, "goodput": 1261568}, "timeStamp": "2013-03-04 10:49:58:591"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 6995968, "goodput": 1794048}, "timeStamp": "2013-03-04 10:49:58:715"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 8314880, "goodput": 1318912}, "timeStamp": "2013-03-04 10:49:58:891"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 9396224, "goodput": 1081344}, "timeStamp": "2013-03-04 10:49:59:014"}, {"eventType": "TransferStatusEvent", "data": {"dataTransfered": 9596928, "goodput": 200704}, "timeStamp": "2013-03-04 10:49:59:142"}, {"eventType": "TransferStopEvent", "data": {"reason": "file transfer finished"}, "timeStamp": "2013-03-04 10:49:59:219"}], "metaData": {"testData": {"data": "testdata10MB", "host": "130.240.5.189", "protocol": "tcp"}, "client": "1", "testType": "GoodputTest", "name": "mytest"}}';
    //$rawData = '{"events": [{"eventType": "TransferStartEvent", "data": {"accessPointName": "eduroam"}, "timeStamp": "2013-03-06 10:31:48:326"}, {"eventType": "TransferStopEvent", "data": {"reason": "file transfer finished"}, "timeStamp": "2013-03-06 10:31:51:617"}], "metaData": {"testData": {"data": "testdata10MB", "host": "130.240.5.189", "protocol": "tcp"}, "client": "1", "testType": "GoodputTest", "name": "new_test"}}';
    $obj = json_decode($rawData, true);

    // Validate test type
    if (!isValidTestType($obj['metaData']['testType'])) {
        throw new JSONException('Test type specified does not exist. Please know what you are doing.', 500);
    }

    // Insert test into db
    $meta = $obj['metaData'];
    $test = new Test();
    $test->name = $meta['name'];
    $test->type = $meta['testType'];
    $test->client_id = $meta['client'];
    $testData = new TestData();
    $testData->data = json_encode($meta['testData']);
    $testData->save();
    $test->testdata_id = $testData->id;
    $test->save();

    // Validate and insert events into db
    foreach ($obj["events"] as $event) {
        if (!isValidEventType($event['eventType'])) {
            throw new JSONException('Some of the events have types that do not exist. Please know what you are doing', 500);
        }
        $insert = new Event();
        $insert->type = $event['eventType'];
        $insert->timestamp = $event['timeStamp'];
        $insert->test_id = $test->id;
        $insert->data = json_encode($event['data']);
        $insert->save();
    }

    // Notify pusher and sender
    $app->pusher->trigger('tests', 'new-test', array('message' => 'Hello'));
    rest_output(array('success' => array('message' => 'Test successfully received and processed.', 'code' => 200)));
});