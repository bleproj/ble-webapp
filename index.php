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
require 'app/routes/log.php';
require 'app/routes/docs.php';
require 'app/routes/events.php';
require 'app/routes//api/checkins.php';
require 'app/routes/api/authenticate.php';

// Run
require 'app/unbootstrap.php';
