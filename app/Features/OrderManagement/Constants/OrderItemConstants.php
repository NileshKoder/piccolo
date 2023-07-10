<?php

namespace App\Features\OrderManagement\Constants;

interface OrderItemConstants
{
    public const CREATED = "CREATED";
    public const PARTIAL_MAPPED = "PARTIAL_MAPPED";
    public const MAPPED = "MAPPED";
    public const TRANSFERED = "TRANSFERED";
    public const CANCELLED = "CANCELLED";

    public const STATES = [
        self::CREATED => self::CREATED,
        self::PARTIAL_MAPPED => self::PARTIAL_MAPPED,
        self::MAPPED => self::MAPPED,
        self::TRANSFERED => self::TRANSFERED,
        self::CANCELLED => self::CANCELLED,
    ];
}
