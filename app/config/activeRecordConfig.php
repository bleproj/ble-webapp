<?php

ActiveRecord\Config::initialize(function($cfg){
	$cfg->set_model_directory('/var/www/bleserver/app/models/');
	$cfg->set_connections(array('development' => 'mysql://bledmin:badPASSWORD@localhost/ble'));	
});
