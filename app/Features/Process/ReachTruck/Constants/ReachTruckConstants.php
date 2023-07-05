<?php

namespace App\Features\Process\ReachTruck\Constants;

interface ReachTruckConstants
{
    public const CREATE_RULES = [
        "transfered_by" => 'required|exists:users,id',
        "reach_truck_id" => 'required|exists:reach_trucks,id',
        "from_locationable_type" => 'required',
        "from_locationable_id" => 'required',
        "to_locationable_type" => 'required',
        "to_locationable_id" => 'required',
    ];
}
