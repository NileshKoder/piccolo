<?php

namespace App\Features\OrderManagement\Constants;

interface OrderItemConstants
{
    public const CREATED = "CREATED";
    public const PARTIAL_MAPPED = "PARTIAL_MAPPED";
    public const MAPPED = "MAPPED";
    public const PARTIAL_TRANSFERRED = "PARTIAL_TRANSFERRED";
    public const TRANSFERRED = "TRANSFERRED";
    public const CANCELLED = "CANCELLED";

    public const STATES = [
        self::CREATED => self::CREATED,
        self::PARTIAL_MAPPED => self::PARTIAL_MAPPED,
        self::MAPPED => self::MAPPED,
        self::PARTIAL_TRANSFERRED => self::PARTIAL_TRANSFERRED,
        self::TRANSFERRED => self::TRANSFERRED,
        self::CANCELLED => self::CANCELLED,
    ];
}
