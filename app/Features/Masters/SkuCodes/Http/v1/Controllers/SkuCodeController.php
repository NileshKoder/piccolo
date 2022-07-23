<?php

namespace App\Features\Masters\SkuCodes\Http\v1\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Masters\SkuCodes\Actions\SkuCodeAction;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\SkuCodes\Http\v1\Requests\StoreSkuCodeRequest;
use App\Features\Masters\SkuCodes\Http\v1\Requests\UpdateSkuCodeRequest;

class SkuCodeController extends Controller
{
    private $skuCodeAction;

    public function __construct(SkuCodeAction $skuCodeAction)
    {
        $this->skuCodeAction = $skuCodeAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.masters.sku-code.index');
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
    public function store(StoreSkuCodeRequest $request)
    {
        try {
            $data = $request->toFormData();
            $this->skuCodeAction->createSkuCode($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('sku-codes.index')->with('success', 'Sku Code Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Masters\SkuCodes\Domains\Models\SkuCode  $skuCode
     * @return \Illuminate\Http\Response
     */
    public function show(SkuCode $skuCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Masters\SkuCodes\Domains\Models\SkuCode  $skuCode
     * @return \Illuminate\Http\Response
     */
    public function edit(SkuCode $skuCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Masters\SkuCodes\Domains\Models\SkuCode  $skuCode
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSkuCodeRequest $request, SkuCode $skuCode)
    {
        try {
            $data = $request->toFormData();
            $this->skuCodeAction->updateSkuCode($skuCode, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('sku-codes.index')->with('success', 'Sku Code Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Masters\SkuCodes\Domains\Models\SkuCode  $skuCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(SkuCode $skuCode)
    {
        //
    }

    public function getSkuCodes(Request $request)
    {
        try {
            $data = $this->skuCodeAction->getSkuCodes(
                $request->search['value'],
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
        return $data;
    }
}
