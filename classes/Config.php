<?php
/**
 * Class Config
 * Used to get parameters defined in init.php
 *
 */
class Config
{
    // function returns parameters from init.php $GLOBALS array
    public static function get($path = null){
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach($path as $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }

        return false;
    }
}