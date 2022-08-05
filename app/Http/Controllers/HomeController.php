<?php

namespace App\Http\Controllers;

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
        $data['most_sku_used'] = PalletDetails::with('skuCode')
            ->select('sku_code_id', DB::raw('COUNT(*) as count'))
            ->groupBy('sku_code_id')->having(DB::raw('COUNT(*)'), '>=', 1)
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->limit(1)
            ->first();

        return view('home', compact('data'));
    }
}
