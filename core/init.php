<?php
// START A SESSION ON ALL PAGES
session_start();
// DEFINE AN ARRAY THAT IS GLOBAL === CAN BE USED ANYWHERE
$GLOBALS['config'] = [
    'mysql' => [
        'host'      => '127.0.0.1',
        'username'  => 'root',
        'password'  => 'geslo123',
        'db_name'   => 'login_system'
    ],
    'remember' => [
        'cookie_name'   => 'hash',
        'cookie_expiry' => '86400'
    ],
    'session' => [
        'session_name'  => 'user',
        'token_name'    => 'token'
    ]
];
// FOR AUTO LOADING CLASSES WHEN NEEDED
spl_autoload_register(function($class){
    require_once 'classes/' . $class . '.php';
});

require 'functions/sanitize.php';