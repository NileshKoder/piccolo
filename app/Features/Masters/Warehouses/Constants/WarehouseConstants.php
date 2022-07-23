<?php

namespace App\Features\Masters\Warehouses\Constants;

interface WarehouseConstants
{
    public const GENERAL = "GENERAL";
    public const NORMAL = "NORMAL";

    public const TYPES = [
        self::GENERAL,
        self::NORMAL,
    ];

    public const GENERAL_ID = 1;
}
