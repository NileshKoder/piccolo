<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'General',
            "type" => "GENERAL",
            "is_empty" => true,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);
    }
}
