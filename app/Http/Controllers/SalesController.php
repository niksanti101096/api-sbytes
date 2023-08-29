<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'AccountId' => 'required',
            'ProductId' => 'required',
            'Price' => 'required',
            'Quantity' => 'required',
            'TotalPrice' => 'required',
            'Address' => 'required',
            'ContactNumber' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $ReferenceId = Str::random(5) . date("YmdHis");
            Sales::create($request->post() + ["ReferenceId" => $ReferenceId]);

            return response()->json(
                [
                    "Message" => "Successfully placed your order!",
                ]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "Message" => "Error while while placing your order!",
                "Error" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
