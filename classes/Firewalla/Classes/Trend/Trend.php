<?php

namespace Firewalla\Classes\Trend;

use Firewalla\Classes\Base\Base;

class Trend extends Base
{
    public \DateTime $ts;

    public int $value;

    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "ts" => \DateTime::class,
            "value" => "int"
        ];
    }
}