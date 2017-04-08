<?php
/**
 * Created by PhpStorm.
 * User: grega
 * Date: 4. 04. 2017
 * Time: 19:05
 */
function sanitize($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}