<?php

use Atenas\Middlewares\TokenGeneratorMiddleware;

$app->get('/', function (){
    print_r(json_encode(["status" => "Working!"]));
});

$app->post('/users', 'RegisterUserController:register');

$app->post('/signin', 'SignInUserController:login')->add(new TokenGeneratorMiddleware());

$app->put('/users/{username}','ActivateUserController:activate');