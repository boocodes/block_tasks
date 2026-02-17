<?php
namespace Task5\Infrastructure;
require_once 'vendor/autoload.php';
use Task5\Core\App;

$config = require_once "./src/config.php";

App::run($config);