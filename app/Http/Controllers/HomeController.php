<?php

namespace App\Http\Controllers;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Domains\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['filled_pallets'] = MasterPallet::isEmpty(false)->count();
        $data['unfilled_pallets'] = MasterPallet::isEmpty(true)->count();
        $data['filled_warehouses'] = Warehouse::isEmpty(false)->count();
        $data['unfilled_warehouses'] = Warehouse::isEmpty(true)->count();
        $data['active_orders'] = Order::notInState([Order::COMPLETED, Order::CANCELLED])->count();

        return view('home', compact('data'));
    }
}
