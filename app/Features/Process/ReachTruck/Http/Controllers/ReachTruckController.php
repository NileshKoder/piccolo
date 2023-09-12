<?php

namespace App\Features\Process\ReachTruck\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\Process\ReachTruck\Http\Requests\StoreReachTruchRequest;
use App\Features\Process\ReachTruck\Http\Requests\UpdateReachTruchRequest;
use Illuminate\View\View;

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
     * @return View
     */
    public function index(): View
    {
        return view('features.process.reach-truck.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
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
     * @param  StoreReachTruchRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreReachTruchRequest $request): RedirectResponse
    {
        try {
            $requestData = $request->toFormData();
            $this->reachTruckAction->processTransferPallet($requestData);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }

        return redirect()->route('reach-trucks.index')->with('success', "Pallet Transfered Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  ReachTruck  $reachTruck
     * @return void
     */
    public function show(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ReachTruck  $reachTruck
     * @return View
     */
    public function edit(ReachTruck $reachTruck): View
    {
        $reachTruck = ReachTruck::with('fromLocationable')->find($reachTruck->id);
        $data = $this->reachTruckAction->getEditData($reachTruck);

        return view('features.process.reach-truck.edit', compact('data', 'reachTruck'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateReachTruchRequest $request
     * @param  ReachTruck  $reachTruck
     * @return RedirectResponse
     */
    public function update(UpdateReachTruchRequest $request, ReachTruck $reachTruck): RedirectResponse
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
     * @param  ReachTruck  $reachTruck
     * @return void
     */
    public function destroy(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getReachTrucks(Request $request): JsonResponse
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
