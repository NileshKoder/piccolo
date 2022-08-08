<?php

namespace App\Features\Process\ReachTruck\Http\Controllers;

use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function show(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function edit(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReachTruck  $reachTruck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReachTruck  $reachTruck
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
