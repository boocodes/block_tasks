<?php
require_once 'vendor/autoload.php';
use Task4\Core\App;

$config = require_once "./src/config.php";

App::run($config);