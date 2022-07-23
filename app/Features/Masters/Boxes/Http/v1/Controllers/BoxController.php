<?php

namespace App\Features\Masters\Boxes\Http\v1\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Masters\Boxes\Actions\BoxAction;
use App\Features\Masters\Boxes\Domains\Models\Box;
use App\Features\Masters\Boxes\Http\v1\Requests\StoreBoxRequest;
use App\Features\Masters\Boxes\Http\v1\Requests\UpdateBoxRequest;

class BoxController extends Controller
{
    private $boxAction;

    public function __construct(BoxAction $boxAction)
    {
        $this->boxAction = $boxAction;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.masters.box-code.index');
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
    public function store(StoreBoxRequest $request)
    {
        try {
            $data = $request->toFormData();
            $this->boxAction->createBox($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('boxes.index')->with('success', 'Box Code Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Features\Masters\Boxes\Domains\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function show(Box $box)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\Masters\Boxes\Domains\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function edit(Box $box)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\Masters\Boxes\Domains\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBoxRequest $request, Box $box)
    {
        try {
            $data = $request->toFormData();
            $this->boxAction->updateBox($box, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('boxes.index')->with('success', 'Box Code Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\Masters\Boxes\Domains\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function destroy(Box $box)
    {
        try {
            if (!$box->checkIsEmpty()) {
                throw new Exception("This box can't delete becuase this crate code is not empty");
            }

            $this->boxAction->deleteBox($box);
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
    public function getBoxes(Request $request)
    {
        try {
            $data = $this->boxAction->getBoxes(
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

    public function getEmptyBoxesByName(Request $request)
    {
        try {
            $data = $this->boxAction->getEmptyBoxesByName($request->name);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }
}
