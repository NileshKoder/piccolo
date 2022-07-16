<?php

namespace App\Features\Masters\Warehouses\Http\v1\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Features\Masters\Warehouses\Actions\WarehouseAction;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Masters\Warehouses\Http\v1\Requests\StoreWarehouseRequest;
use App\Features\Masters\Warehouses\Http\v1\Requests\UpdateWarehouseRequest;

class WarehouseController extends Controller
{
    private $warehouseAction;

    public function __construct(WarehouseAction $warehouseAction)
    {
        $this->warehouseAction = $warehouseAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.masters.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Warehouse::TYPES;
        return view('features.masters.warehouse.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouseRequest $request)
    {
        try {
            $data = $request->toFormData();
            $this->warehouseAction->createWarehouse($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Masters\Warehouses\Domains\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Masters\Warehouses\Domains\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $types = Warehouse::TYPES;
        return view('features.masters.warehouse.edit', compact('types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Masters\Warehouses\Domains\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        try {
            $data = $request->toFormData();
            $this->warehouseAction->updateWarehouse($warehouse, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Masters\Warehouses\Domains\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            if (!$warehouse->checkIsEmpty()) {
                throw new Exception("This warehouse can't delete becuase this warehouse is not empty");
            }

            return $this->warehouseAction->deleteWarehouse($warehouse);
        } catch (Exception $ex) {
            return response()->json(['status' => 500, 'error' => $ex->getMessage()]);
        }
    }

    public function getWarehouses(Request $request)
    {
        try {
            $data = $this->warehouseAction->getWarehouses(
                $request->search['value'],
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            Log::info($ex);
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }
}
