<?php
require_once 'core/init.php';
// if session is set, output the flash message
if(Session::exists('home')) {
    echo Session::flash('home');
}