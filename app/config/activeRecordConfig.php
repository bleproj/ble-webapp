<?php

$cfg = ActiveRecord\Config::instance();
$cfg->set_model_directory('app/models/');
$cfg->set_connections(array('development' => 'mysql://bledmin:badPASSWORD@localhost/ble'));