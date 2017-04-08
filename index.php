<?php
require_once 'core/init.php';

// pridobim povezavo z bazo na SINGLETON način, kar pomeni da imamo samo eno povezavo vzpostavljeno z bazo
$user = DB::getInstance();

$user->update('users', 2,
    ['password' => 'newPassword',]);

