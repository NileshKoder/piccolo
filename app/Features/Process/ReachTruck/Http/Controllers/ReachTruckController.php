<?php

namespace App\Features\Process\ReachTruck\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\Process\ReachTruck\Http\Requests\StoreReachTruchRequest;
use App\Features\Process\ReachTruck\Http\Requests\UpdateReachTruchRequest;

class ReachTruckController extends Controller
{
    private $reachTruckAction;

    public function __construct(ReachTruckAction $reachTruckAction)
    {
        $this->reachTruckAction = $reachTruckAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.process.reach-truck.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (empty($request->type)) {
            $locationCounts = $this->reachTruckAction->getLocationCount();
            return view('features.process.reach-truck.location-count-list', compact('locationCounts'));
        }

        $data = $this->reachTruckAction->getCreateData($request->type);
        return view('features.process.reach-truck.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReachTruchRequest $request)
    {
        try {
            $requestData = $request->toFormData();
            $this->reachTruckAction->processTransferPallet($requestData);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }

        return redirect()->route('reach-trucks.index')->with('success', "Pallet Transfered Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function show(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function edit(ReachTruck $reachTruck)
    {
        $reachTruck = ReachTruck::with('fromLocationable')->find($reachTruck->id);
        $data = $this->reachTruckAction->getEditData($reachTruck);

        return view('features.process.reach-truck.edit', compact('data', 'reachTruck'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReachTruchRequest $request, ReachTruck $reachTruck)
    {
        try {
            $requestData = $request->toFormData();
            $this->reachTruckAction->processUpdateTransferPalletDetails($requestData, $reachTruck);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }

        return redirect()->route('reach-trucks.index')->with('success', "Pallet Transfered Details Updatd Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReachTruck $reachTruck)
    {
        //
    }

    public function getRechTrucks(Request $request)
    {
        try {
            $data = $this->reachTruckAction->getReachTrucks(
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }

    public function getPalletForReachTruck(Request $request)
    {
        try {
            $data = $this->reachTruckAction->getPalletForReachTruck($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }
}
