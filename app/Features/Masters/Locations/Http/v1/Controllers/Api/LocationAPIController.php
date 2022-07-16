<?php

namespace App\Features\Masters\Locations\Http\v1\Controllers\Api;

use Illuminate\Http\Request;
use App\Features\Common\ApiTrait;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Http\Controllers\Controller;

class LocationAPIController extends Controller
{
    use ApiTrait;

    public function getLocations(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        $locations = Location::all();

        return response()->json($locations, 200);
    }
}
