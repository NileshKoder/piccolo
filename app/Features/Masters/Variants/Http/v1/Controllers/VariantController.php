<?php

namespace App\Features\Masters\Variants\Http\v1\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Masters\Variants\Actions\VariantsAction;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Variants\Http\v1\Requests\StoreVariantRequest;
use App\Features\Masters\Variants\Http\v1\Requests\UpdateVariantRequest;

class VariantController extends Controller
{
    private $variantsAction;

    public function __construct(VariantsAction $variantsAction)
    {
        $this->variantsAction = $variantsAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.masters.variant.index');
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
    public function store(StoreVariantRequest $request)
    {
        try {
            $data = $request->toFormData();
            $this->variantsAction->createVariant($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('variants.index')->with('success', 'Variant Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Masters\Variants\Domains\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Masters\Variants\Domains\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function edit(Variant $variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Masters\Variants\Domains\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request, Variant $variant)
    {
        try {
            $data = $request->toFormData();
            $this->variantsAction->updateVariant($data, $variant);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('variants.index')->with('success', 'Variant Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Masters\Variants\Domains\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {
        //
    }

    public function getVariants(Request $request)
    {
        try {
            $data = $this->variantsAction->getVariants(
                $request->search['value'],
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
