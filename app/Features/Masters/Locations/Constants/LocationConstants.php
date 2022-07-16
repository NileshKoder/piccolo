<?php

namespace App\Features\Masters\Locations\Constants;

interface LocationConstants
{
    public const GLASS = "GLASS";
    public const CERAMIC = "CERAMIC";
    public const RECYCLE = "RECYCLE";
    public const CURING = "CURING";
    public const LINES = "LINES";
    public const LOADING = "LOADING";
    public const FG_PALLET = "FG_PALLET";

    public const TYPES = [
        self::GLASS => self::GLASS,
        self::CERAMIC => self::CERAMIC,
        self::RECYCLE => self::RECYCLE,
        self::LINES => self::LINES,
        self::LOADING => self::LOADING,
        self::FG_PALLET => self::FG_PALLET,
    ];

    public const TYPE_COUNTES = [
        self::GLASS => 0,
        self::CERAMIC => 0,
        self::RECYCLE => 0,
        self::LINES => 0,
        self::LOADING => 0,
        self::FG_PALLET => 0,
    ];
}