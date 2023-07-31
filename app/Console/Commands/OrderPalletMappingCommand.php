<?php

namespace App\Console\Commands;

use App\Features\OrderManagement\Domains\Models\OrderItem;
use Exception;
use Illuminate\Console\Command;

class OrderPalletMappingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:orderPalletMappingCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will be mapped pallets to order which are created and partial mapped';

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
        $orderItems = OrderItem::stateIn([OrderItem::CREATED, OrderItem::PARTIAL_MAPPED])
            ->pickUpDateLessThanToday()
            ->orderBy('pick_up_date', 'ASC')->get();

        try {
            foreach ($orderItems as $key => $orderItem) {
                $orderItem->mapPallets();
            }
        } catch (Exception $ex) {
            info($ex);
        }

        return;
    }
}
