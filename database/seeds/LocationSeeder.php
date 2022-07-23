<?php

use Illuminate\Database\Seeder;
use App\Features\Masters\Locations\Domains\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = $this->getLocations();
        foreach ($locations as $location) {
            Location::updateOrCreate($location);
        }
    }

    public function getLocations(): array
    {
        return [
            // Glass locations
            ["name" => "Glass A", "abbr" => "GLA", "type" => "GLASS"],
            ["name" => "Glass B", "abbr" => "GLB", "type" => "GLASS"],
            ["name" => "Glass C", "abbr" => "GLC", "type" => "GLASS"],

            // Ceramic locations
            ["name" => "Ceramic A", "abbr" => "CA", "type" => "CERAMIC"],
            ["name" => "Ceramic B", "abbr" => "CB", "type" => "CERAMIC"],
            ["name" => "Ceramic C", "abbr" => "CC", "type" => "CERAMIC"],

            // Recycle locations
            ["name" => "Recycle A", "abbr" => "RA", "type" => "RECYCLE"],
            ["name" => "Recycle B", "abbr" => "RB", "type" => "RECYCLE"],

            // Line locations
            ["name" => "Line 1", "abbr" => "L1", "type" => "LINES"],
            ["name" => "Line 2", "abbr" => "L2", "type" => "LINES"],
            ["name" => "Line 3", "abbr" => "L3", "type" => "LINES"],
            ["name" => "Line 4", "abbr" => "L4", "type" => "LINES"],
            ["name" => "Line 5", "abbr" => "L5", "type" => "LINES"],
            ["name" => "Line 6", "abbr" => "L6", "type" => "LINES"],
            ["name" => "Line 7", "abbr" => "L7", "type" => "LINES"],
            ["name" => "Line 8", "abbr" => "L8", "type" => "LINES"],
            ["name" => "Line 9", "abbr" => "L9", "type" => "LINES"],
            ["name" => "Line 10", "abbr" => "L10", "type" => "LINES"],
            ["name" => "Line 11", "abbr" => "L11", "type" => "LINES"],
            ["name" => "Line 12", "abbr" => "L12", "type" => "LINES"],
            ["name" => "Line 13", "abbr" => "L13", "type" => "LINES"],

            // Line locations
            ["name" => "Curing", "abbr" => "CU", "type" => "CURING"],

            // Line locations
            ["name" => "Loading", "abbr" => "L", "type" => "LOADING"],
        ];
    }
}
