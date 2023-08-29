<?php

namespace App\Console\Commands;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateReachTruckForLoading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-reach-truck-for-wh-loading';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pallets = Pallet::with('reachTruck')
                ->whereDoesntHave('reachTruck', function($q) {
                    $q->where('to_locationable_type', Location::class)
                        ->where('to_locationable_id', Location::LOADING_LOCATION_ID);
                })
                ->whereNotNull('loading_transfer_date')
                ->whereDate('loading_transfer_date', '<=', Carbon::today())
                ->get();
        foreach ($pallets as $pallet)
        {
            DB::beginTransaction();
            if($pallet->reachTruck)
            {
                $pallet->reachTruck->updateForLoadingTransfer();
            }
            DB::commit();
        }
    }
}
