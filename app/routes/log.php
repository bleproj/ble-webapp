<?php

// The home page
$app->get('/log', function () use ($app) {

    $data['section'] = 'log';
    $app->render('log.twig', $data);
});

// The home page
$app->get('/log/clear', function () use ($app) {

    $oldLogFile = "/var/www/bleserver/app/log/log.txt";
    $newLogFile = "/var/www/bleserver/app/log/log-" . date('Y-m-d-H:i:s') . ".txt";

    if (filesize($oldLogFile) != 0) {
        // Duplicate
        if (copy($oldLogFile, $newLogFile)) {
            file_put_contents($oldLogFile, "");
            $response['message'] = "Log cleared and backed up to file log-" . date('Y-m-d-H:i:s') . ".txt";
            $response['success'] = 1;
            rest_output($response);
        } else {
            throw new JSONException('Could not duplicate log file', 500);
        }
    } else {
        $response['message'] = "Log already empty!";
        $response['success'] = 0;
        rest_output($response);
    }
});