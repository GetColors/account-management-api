<?php

use Atenas\Middlewares\TokenGeneratorMiddleware;
use Atenas\Middlewares\TokenValidationMiddleware;

$app->get('/', function (){

    $databaseStatus = false;
    $serviceStatus = false;

    $connection = new PDO("pgsql:dbname=atenas;host=postgresql", 'admin', '00000000');


    if($connection) {
        $databaseStatus = true;
    }

    if ($databaseStatus){
        $serviceStatus = true;
    }

    print_r(
        json_encode(
            [
                "databaseStatus" => $databaseStatus,
                "status" => $serviceStatus
            ]
        )
    );
});

$app->post('/register', 'RegisterUserController:register');

$app->post('/signin', 'SignInUserController:login')->add(new TokenGeneratorMiddleware());

$app->patch('/users/{username}','ActivateUserController:activate');

$app->patch('/users/{username}/account', 'ChangePasswordUserController:changePassword')->add(new TokenValidationMiddleware());