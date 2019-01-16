<?php

use Atenas\Controllers\RegisterUserController;

$container['RegisterUserController'] = function ($container){
    return new RegisterUserController($container);
};