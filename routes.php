<?php

use Atenas\Middlewares\TokenGeneratorMiddleware;
use Atenas\Middlewares\TokenValidationMiddleware;

$app->get('/', function (){
    print_r(json_encode(["status" => "Working!"]));
});

$app->post('/register', 'RegisterUserController:register');

$app->post('/signin', 'SignInUserController:login')->add(new TokenGeneratorMiddleware());

$app->put('/users/{username}','ActivateUserController:activate');

$app->patch('/changepassword/{username}', 'ChangePasswordUserController:changePassword')->add(new TokenValidationMiddleware());