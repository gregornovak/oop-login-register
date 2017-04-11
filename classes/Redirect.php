<?php

class Redirect
{
    // redirect user to desired location, either 404 page or something else
    public static function to($location = null)
    {
        if($location) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header("HTTP/1.0 404 Not Found");
                        include 'includes/errors/404.php';
                        exit();
                    break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}