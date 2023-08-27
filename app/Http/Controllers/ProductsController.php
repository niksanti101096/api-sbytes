<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return response()->json(['status' => '200', 'products' => $products]);
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
            'ProductCategoryName' => 'required',
            'ProductSeriesName' => 'required',
            'ProductModel' => 'required',
            'Stock' => 'required',
            'Price' => 'required',
            'ImageUrl' => 'required|image',
            'Cpu' => 'required',
            'Memory' => 'required',
            'IntegratedGfx' => 'required',
            'Storage' => 'required',
            'ScreenSize' => 'required',
            'Resolution' => 'required',
            'RefreshRate' => 'required',
            'Color' => 'required',
            'Battery' => 'required',
            'OperatingSystem' => 'required',
            'Package' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $imagePath = Str::random() . '.' . $request->ImageUrl->getClientOriginalExtension();

            Storage::putFileAs("/public/products/images", $request->ImageUrl, $imagePath, 'public');

            Products::create($request->post() + ["ImageUrl" => $imagePath]);

            return response()->json(
                [
                    "Message" => "Successfully created a new product!",
                    "ImageUrl" => $imagePath
                ]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "Message" => "Error while creating a product!"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        $validator = Validator::make($request->all(), [
            'ProductCategoryName' => 'required',
            'ProductSeriesName' => 'required',
            'ProductModel' => 'required',
            'Stock' => 'required',
            'Price' => 'required',
            'ImageUrl' => 'nullable',
            'Cpu' => 'required',
            'Memory' => 'required',
            'IntegratedGfx' => 'required',
            'Storage' => 'required',
            'ScreenSize' => 'required',
            'Resolution' => 'required',
            'RefreshRate' => 'required',
            'Color' => 'required',
            'Battery' => 'required',
            'OperatingSystem' => 'required',
            'Package' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $product->fill($request->all())->update();
            if ($request->hasFile('ImageUrl')) {
                if ($product->ImageUrl) {
                    $oldImage = Storage::exists("/public/products/images/{$product->ImageUrl}");

                    if ($oldImage) {
                        Storage::delete("/public/products/images/{$product->ImageUrl}");
                    }
                }
                $imagePath = Str::random() . '.' . $request->ImageUrl->getClientOriginalExtension();

                Storage::putFileAs("/public/products/images", $request->ImageUrl, $imagePath, 'public');
                $product->ImageUrl = $imagePath;
                $product->save();
            }

            return response()->json([
                "Message" => "Product updated successfully!",
                "Name" => $product->ProductSeriesName
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "message" => "Error while updating the product!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        try {
            if ($product->ImageUrl) {
                $oldImage = Storage::exists("/public/products/images/{$product->ImageUrl}");
    
                if ($oldImage) {
                    Storage::delete("/public/products/images/{$product->ImageUrl}");
                }
            }
            $product->delete();
            return response()
                ->json(
                    [
                        "Message" => "Product deleted successfully"
                    ]
                );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "Message" => "There's an error deleting the product!"
            ]);
        }
    }
}