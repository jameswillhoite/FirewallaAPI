<?php

namespace Firewalla\Classes\Group;

use Firewalla\Classes\Base\Base;

class Group extends Base
{

    /**
     * The group’s unique identifier
     * @var string
     */
    public string $id;

    /**
     * Name of the device group
     * @var string
     */
    public string $name;


    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "id" => "string",
            "name" => "string"
        ];
    }
}