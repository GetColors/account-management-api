<?php

use Atenas\Controllers\ActivateUserController;
use Atenas\Controllers\RegisterUserController;

$container['RegisterUserController'] = function ($container){
    return new RegisterUserController($container);
};

$container['ActivateUserController'] = function ($container){
    return new ActivateUserController($container);
};