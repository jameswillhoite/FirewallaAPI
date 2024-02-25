<?php

namespace Firewalla\Classes\Request;

use Firewalla\Classes\Request\GetListRequest;

class GetListBoxesRequest extends GetListRequest
{
    public string $boxGroup;
}