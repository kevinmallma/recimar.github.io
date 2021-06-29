<?php
require_once '../vendor/autoload.php';//importaciones
require_once 'templates/funciones.php';
define('__CONFIG__', [
    'db' => [
        'host' => 'mysql:host=localhost;dbname=recimar;charset=utf8',
        'user' => 'root',
        'password' => '',
    ],
    'log' => [
        'path' => 'historial/',
        'channel' => 'recicladora',
    ]
]);

