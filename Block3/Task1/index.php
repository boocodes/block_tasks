<?php

use Task1\Application\App;
use Task1\Infrastructure\Request\Request;
use Task1\Infrastructure\Response\Response;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());




$appInstance->addGetRoute('/health', function (Request $request) {
    new Response()->json(['status' => 'ok'], 200);
});
$appInstance->addPostRoute('/echo', function (Request $request) {
    var_dump(get_declared_classes());
    new Response()->jsonWithValidate($request->getBody(), 200);
});
$appInstance->addGetRoute('/headers', function () {
   $result = [
       'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
        'Accept' => $_SERVER['HTTP_ACCEPT']
   ];
   if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
       $result ['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
   }

   new Response()->json($result, 200);
});


$appInstance->run();
