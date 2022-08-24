<?php

namespace App\Features\Process\PalletManagement\Http\v1\Controllers;

use App\Features\Process\PalletManagement\Actions\PalletAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Http\v1\Requests\StorePalletRequest;
use App\Features\Process\PalletManagement\Http\v1\Requests\UpdatePalletRequest;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use Exception;

class PalletController extends Controller
{
    public $palletAction;
    public $reachTruckAction;
    public function __construct(
        PalletAction $palletAction,
        ReachTruckAction $reachTruckAction
    ) {
        $this->palletAction = $palletAction;
        $this->reachTruckAction = $reachTruckAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("features.process.pallet-management.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->palletAction->getMasterData();
        return view("features.process.pallet-management.create", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePalletRequest $request)
    {
        try {
            $requestData = $request->requestData();
            $pallet = $this->palletAction->createPallet($requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Filled Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Process\PalletManagement\Domains\Models\Pallet  $pallet
     * @return \Illuminate\Http\Response
     */
    public function show(Pallet $pallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Process\PalletManagement\Domains\Models\Pallet  $pallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Pallet $pallet)
    {
        $data = $this->palletAction->getMasterDataForEdit($pallet);
        return view("features.process.pallet-management.edit", compact('data', 'pallet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Process\PalletManagement\Domains\Models\Pallet  $pallet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePalletRequest $request, Pallet $pallet)
    {
        try {
            $requestData = $request->requestData();
            $this->palletAction->updatePallet($pallet, $requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Process\PalletManagement\Domains\Models\Pallet  $pallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pallet $pallet)
    {
        //
    }

    public function getAllPallets(Request $request)
    {
        try {
            $data = $this->palletAction->getPallets(
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }
}