<?php
$dotenv = Dotenv\Dotenv::create(__DIR__ . "/../");
$dotenv->load();

define("DB_CONFIG", array(
        "driver" => getenv("DB_DRIVER"),
        "host" => getenv("DB_HOST"),
        "database" => getenv("DATABASE"),
        "username" => getenv("DB_USER"),
        "password" => getenv("DB_PASSWORD"),
        "charset" => getenv("DB_CHARSET"),
        "collation" => getenv("DB_COLLATION"),
        "prefix" => getenv("DB_PREFIX"),
    )
);