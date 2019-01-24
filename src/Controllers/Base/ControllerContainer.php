<?php

use Atenas\Controllers\ActivateUserController;
use Atenas\Controllers\RegisterUserController;
use Atenas\Controllers\SignInUserController;

$container['RegisterUserController'] = function ($container){
    return new RegisterUserController($container);
};

$container['ActivateUserController'] = function ($container){
    return new ActivateUserController($container);
};

$container['SignInUserController'] = function ($container) {
    return new SignInUserController($container);
};

$container['ChangePasswordUserController'] = function ($container) {
    return new ChangePasswordUserController($container);
};