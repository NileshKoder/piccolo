<?php

namespace App\Features\OrderManagement\Constants;

interface OrderConstants
{
    public const DRAFT = 'DRAFT';
    public const READY_TO_MAPPING = 'READY_TO_MAPPING';
    public const TRANSFERRING_PALLETS = 'TRANSFERRING_PALLETS';
    public const TRANSFERRED = 'TRANSFERRED';
    public const COMPLETED = 'COMPLETED';
    public const CANCELLED = 'CANCELLED';

    public const STATES = [
        self::DRAFT => self::DRAFT,
        self::READY_TO_MAPPING => self::READY_TO_MAPPING,
        self::TRANSFERRING_PALLETS => self::TRANSFERRING_PALLETS,
        self::TRANSFERRED => self::TRANSFERRED,
        self::COMPLETED => self::COMPLETED,
        self::CANCELLED => self::CANCELLED,
    ];

    public const CREATE_RULE = [
        "order_number" => "required",
        "order_item_details.*.sku_code_id" => "required|exists:sku_codes,id",
        "order_item_details.*.variant_id" => "nullable|exists:variants,id",
        "order_item_details.*.location_id" => "nullable|exists:locations,id",
        "order_item_details.*.required_weight" => "required",
        "order_item_details.*.pick_up_date" => "nullable",
    ];
}
