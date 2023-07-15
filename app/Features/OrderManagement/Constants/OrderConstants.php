<?php

namespace App\Features\OrderManagement\Constants;

interface OrderConstants
{
    public const CREATED = 'CREATED';
    public const INPOGRESS = 'INPOGRESS';
    public const COMPLETED = 'COMPLETED';

    public const CREATE_RULE = [
        "order_number" => "required",
        "order_item_details.*.sku_code_id" => "required|exists:sku_codes,id",
        "order_item_details.*.variant_id" => "required|exists:variants,id",
        "order_item_details.*.location_id" => "required|exists:locations,id",
        "order_item_details.*.required_weight" => "required",
        "order_item_details.*.pick_up_date" => "required",
    ];
}