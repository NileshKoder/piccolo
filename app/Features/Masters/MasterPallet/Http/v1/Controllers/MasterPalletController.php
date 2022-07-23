<?php

namespace App\Features\Masters\MasterPallet\Http\v1\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Masters\MasterPallet\Actions\MasterPalletAction;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Masters\MasterPallet\Http\v1\Requests\StoreMatserPalletRequest;
use App\Features\Masters\MasterPallet\Http\v1\Requests\UpdateMatserPalletRequest;

class MasterPalletController extends Controller
{
    private $masterPalletAction;

    public function __construct(MasterPalletAction $masterPalletAction)
    {
        $this->masterPalletAction = $masterPalletAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.masters.master-pallet.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMatserPalletRequest $request)
    {
        try {
            $data = $request->toFormData();
            $this->masterPalletAction->createMasterPallet($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('master-pallets.index')->with('success', 'Master Pallet Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Masters\MasterPallet\Domains\Models\MasterPallet  $masterPallet
     * @return \Illuminate\Http\Response
     */
    public function show(MasterPallet $masterPallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Masters\MasterPallet\Domains\Models\MasterPallet  $masterPallet
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterPallet $masterPallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Masters\MasterPallet\Domains\Models\MasterPallet  $masterPallet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatserPalletRequest $request, MasterPallet $masterPallet)
    {
        try {
            $data = $request->toFormData();
            $this->masterPalletAction->updateMasterPallet($masterPallet, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('master-pallets.index')->with('success', 'Master Pallet Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Masters\MasterPallet\Domains\Models\MasterPallet  $masterPallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterPallet $masterPallet)
    {
        try {
            if (!$masterPallet->checkIsEmpty()) {
                throw new Exception("This pallet can't delete becuase this crate code is not empty");
            }

            $this->masterPalletAction->deleteMasterPallet($masterPallet);
        } catch (Exception $ex) {
            return response()->json(['status' => 500, 'error' => $ex->getMessage()]);
        }
    }

    /**
     * crate code index ajax
     *
     * @param  \Illuminate\Http\Response
     * @return Collection $data
     */
    public function getMasterPallets(Request $request)
    {
        try {
            $data = $this->masterPalletAction->getMasterPallets(
                $request->search['value'],
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            info($ex);
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }
}
