<?php

use Atenas\Middlewares\TokenGeneratorMiddleware;
use Atenas\Middlewares\TokenValidationMiddleware;

$app->get('/', function (){
    print_r(json_encode(["status" => "Working!"]));
});

$app->post('/register', 'RegisterUserController:register');

$app->post('/signin', 'SignInUserController:login')->add(new TokenGeneratorMiddleware());

$app->patch('/users/{username}','ActivateUserController:activate');

$app->patch('/users/{username}/account', 'ChangePasswordUserController:changePassword')->add(new TokenValidationMiddleware());