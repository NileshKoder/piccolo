<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Features\Masters\Pallets\Domains\Models\Pallet;

class PalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 999; $i++) {
            if ($i <= 9) {
                $palletName = "P00{$i}";
            } elseif ($i <= 99) {
                $palletName = "P0{$i}";
            } else {
                $palletName = "P{$i}";
            }

            array_push($data, [
                'name' => $palletName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        DB::beginTransaction();
        Pallet::insert($data);
        DB::commit();
    }
}
