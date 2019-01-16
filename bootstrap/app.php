<?php

require __DIR__ . '/databaseConfig.php';

$app = new Slim\App([
    'settings'  => [
        'displayErrorDetails' => true,

        /*
            ELOQUENT ENVIROMENT CONFIGURATION
         */
        'db' => [
            'driver' => DB_CONFIG['driver'],
            'host' => DB_CONFIG['host'],
            'database' => DB_CONFIG['database'],
            'username' => DB_CONFIG['username'],
            'password' => DB_CONFIG['password'],
            'charset' => DB_CONFIG['charset'],
            'collation' => DB_CONFIG['collation'],
            'prefix' => DB_CONFIG['prefix'],
        ]
    ],
]);
$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){

    return $capsule;
};

require __DIR__ . '/../src/Controllers/Base/ControllerContainer.php';
require __DIR__ . '/../routes.php';