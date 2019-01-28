<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Resources\Store\StoreResource;
use App\Http\Resources\Store\StoresCollection;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return StoresCollection
     */
    public function index()
    {
        return new StoresCollection(Store::all());
    }


    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            Store::create([
               'name' => $request->name,
               'address' => $request->address
            ]);

            return response()->json([
                'msg' => 'Store created.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param Store $store
     * @return StoreResource|\Illuminate\Http\JsonResponse
     */
    public function show(Store $store)
    {
        try {
            return response()->json([
                'store' => new StoreResource($store),
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRequest $request, Store $store)
    {
        try {
            $store->name = $request->name;
            $store->address = $request->address;

            $store->save();

            return response()->json([
                'msg' => 'Store updated.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        try {
            $store->delete();

            return response()->json([
                'msg' => 'Store deleted.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }
}
