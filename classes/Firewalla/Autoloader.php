<?php

namespace Firewalla;

defined("DS") || define("DS", DIRECTORY_SEPARATOR);

spl_autoload_register("Firewalla\\Autoloader::load");

class Autoloader
{
    public static function load($class)
    {
        if(!preg_match("/^\\\?Firewalla\\\/", $class))
        {
            return null;
        }

        $exp = explode("\\", $class);
        array_shift($exp);

        $path = __DIR__ . DS . 'Classes' . DS . implode(DS, $exp) . ".php";

        if(file_exists($path))
        {
            require_once $path;
        }
        else
        {
            return null;
        }

    }
}