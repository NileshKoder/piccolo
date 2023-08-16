<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;

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
        $orderItems = OrderItem::with('order','orderItemPalletDetails')->stateIn([OrderItem::CREATED, OrderItem::PARTIAL_MAPPED, OrderItem::TRANSFERED])
            ->whereHas('order', function ($orderQry) {
                return $orderQry->stateIn([Order::READY_TO_MAPPING, Order::TRANSFERING_PALLETS]);
            })
            ->pickUpDateLessThanToday()
            ->orderBy('pick_up_date', 'ASC')->get();

        try {
            foreach ($orderItems as $key => $orderItem) {
                if($orderItem->required_weight > $orderItem->orderItemPalletDetails->sum('mapped_weight')) {
                    $orderItem->mapPallets();
                    $orderItem->order->updateState(Order::TRANSFERING_PALLETS);
                }
            }
        } catch (Exception $ex) {
            info($ex);
        }

        return;
    }
}
