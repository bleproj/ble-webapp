<?php

// *** Require everything needed to run the app ***

// Setup
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'Twig/Autoloader.php';
Twig_Autoloader::register();

require 'Pusher/Pusher.php';

require 'php-activerecord/ActiveRecord.php';
require 'app/config/activeRecordConfig.php';

require 'Slim-plugins/Twig.php';
require 'app/lib/functions.php';
require 'app/bootstrap.php';

// Routes
require 'app/routes/home.php';
require 'app/routes/login.php';
require 'app/routes/tests.php';
require 'app/routes/clients.php';
require 'app/routes/uploadtest.php';

// Run
require 'app/unbootstrap.php';
