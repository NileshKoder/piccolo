<?php

namespace App\Features\Masters\Locations\Constants;

interface LocationConstants
{
    public const GLASS = "GLASS";
    public const CERAMIC = "CERAMIC";
    public const RECYCLE = "RECYCLE";
    public const LINES = "LINES";
    public const LOADING = "LOADING";
    public const WAREHOUSE = "WAREHOUSE";
    public const FG_PALLET = "FG_PALLET";
    public const LINE_TO_LOADING = "LINE_TO_LOADING";
    public const WH_TO_LINE = "WH_TO_LINE";

    // change names
    public const LOCATION_NAME_CHANGE_LOADING = 'WH TO LOADING';
    public const LOCATION_NAME_CHANGE_LINE = 'ASSEMBLY LINE TO WH';
    public const LOCATION_NAME_CHANGE_WH_TO_LINE = 'WH TO ASSEMBLY LINE';
    public const LOCATION_NAME_CHANGE_LINE_TO_WH = "LINE_TO_WH";
    public const LOCATION_NAME_CHANGE_LINE_TO_LOADING = "ASSEMBLY LINE TO LOADING";

    public const LOADING_LOCATION_ID = 23;
}
