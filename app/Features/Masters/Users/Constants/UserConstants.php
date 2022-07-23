<?php

namespace App\Features\Masters\Users\Constants;

interface UserConstants
{
    public const ACTIVE = "ACTIVE";
    public const INACTIVE = "INACTIVE";

    public const SUPER_ADMIN = "SUPER_ADMIN";
    public const REACH_TRUCK = "REACH_TRUCK";
    public const PALLET_CREATION = "PALLET_CREATION";
    public const FG_PALLET_CREATION = "FG_PALLET_CREATION";

    public const ROLES = [
        self::SUPER_ADMIN,
        self::REACH_TRUCK,
        self::PALLET_CREATION,
        self::FG_PALLET_CREATION,
    ];

    public const DEFAULT_USER = 1;
}
