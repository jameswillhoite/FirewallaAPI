<?php

namespace Firewalla\Classes\Base;

abstract class Base
{
    /**
     * Map of the properties and type
     * @return array
     */
    abstract public static function getParamMap(): array;
}