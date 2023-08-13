<?php

namespace App\Features\Process\PalletManagement\Http\v1\Controllers;

use App\Features\Process\PalletManagement\Actions\PalletAction;
use App\Features\Process\PalletManagement\Http\v1\Requests\StorePalletBoxDetailsRequest;
use App\Features\Process\PalletManagement\Http\v1\Requests\UpdatePalletBoxDetailRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Http\v1\Requests\StorePalletRequest;
use App\Features\Process\PalletManagement\Http\v1\Requests\UpdatePalletRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PalletController extends Controller
{
    public $palletAction;

    public function __construct(PalletAction $palletAction)
    {
        $this->palletAction = $palletAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view("features.process.pallet-management.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $data = $this->palletAction->getMasterData();
        return view("features.process.pallet-management.create", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePalletRequest  $request
     * @return RedirectResponse
     */
    public function store(StorePalletRequest $request): RedirectResponse
    {
        try {
            $requestData = $request->requestData();
            $this->palletAction->createPallet($requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Filled Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  Pallet  $pallet
     * @return void
     */
    public function show(Pallet $pallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Pallet  $pallet
     * @return View
     */
    public function edit(Pallet $pallet): View
    {
        $data = $this->palletAction->getMasterDataForEdit($pallet);
        $pallet->load([
            'palletDetails' => function ($q) {
                $q->with([
                    'orderItemPallet',
                    'skuCode',
                    'variant',
                ]);
            }
        ]);
        return view("features.process.pallet-management.edit", compact('data', 'pallet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePalletRequest  $request
     * @param  Pallet  $pallet
     * @return RedirectResponse
     */
    public function update(UpdatePalletRequest $request, Pallet $pallet): RedirectResponse
    {
        try {
            $requestData = $request->requestData();
            $this->palletAction->updatePallet($pallet, $requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Updated Successfully');
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

    public function createWithBoxDetails()
    {
        $data = $this->palletAction->getMastersForBoxDetails();

        return view('features.process.pallet-management.create-box-details', compact('data'));
    }

    /**
     * @param StorePalletBoxDetailsRequest $request
     * @return RedirectResponse
     */
    public function storeBoxDetails(StorePalletBoxDetailsRequest $request): RedirectResponse
    {
        try {
            $requestData = $request->requestData();
            $this->palletAction->createPallet($requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Filled Successfully');
    }

    public function editWithBoxDetails(Pallet $pallet): View
    {
        $data = $this->palletAction->getMastersForBoxDetailsForEdit($pallet);
        $pallet->load('palletBoxDetails');
        return view("features.process.pallet-management.edit-box-details", compact('data', 'pallet'));
    }

    /**
     * @param UpdatePalletBoxDetailRequest $request
     * @return RedirectResponse
     */
    public function updateWithBoxDetails(UpdatePalletBoxDetailRequest $request, Pallet $pallet): RedirectResponse
    {
        try {
            $requestData = $request->requestData();
            $this->palletAction->updatePallet($pallet, $requestData);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('pallets.index')->with('success', 'Pallet Filled Successfully');
    }

    public function setDateForTransferPalletAtLoading(Request $request)
    {
        try {
            $this->palletAction->setDateForPalleTransferAtLoading($request->id, $request->transfer_date);
        } catch (Exception $exception) {
            Log::error($exception);
            back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Transfer date updated successfully');
    }
}
