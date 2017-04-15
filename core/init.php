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

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_sessions', ['hash', '=', $hash]);

    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}