<?php


$app->get('/', function (){
    print_r(json_encode(["status" => "Working!"]));
});
$app->post('/users', 'RegisterUserController:register');