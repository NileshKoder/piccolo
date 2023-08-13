<?php

namespace App\Console\Commands;

use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $pallets = Pallet::with('reachTruck')->whereNotNull('loading_transfer_date')->whereDate('loading_transfer_date', '<=', Carbon::today())->get();

        foreach ($pallets as $pallet)
        {
            if($pallet->reachTruck)
            {
                $pallet->reachTruck->updateForLoadingTransfer();
            }
        }
    }
}
