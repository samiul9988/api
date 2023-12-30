<?php

namespace App\Http\Controllers;

// use App\Models\Product;
// use Illuminate\Support\Str;
// use Illuminate\Http\Request;
// use App\Http\Requests\ProductRequest;
// use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => $products
        ],200);
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
    public function store(ProductRequest $request)
    {
        try{
            $fileName = Str::random(32).".".$request->image->getClientOriginalExtension();
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $fileName
            ]);


            /**Store image folder*/
            Storage::disk('public')->put($fileName, file_get_contents($request->image));

            // $request->image->storeAs('public', $fileName);

                return response()->json([
                "message" => "Data store successfully"
            ],200);


        }catch(\Exception $e){
            return response()->json([
                "message" => "Data store Failed"
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        if(!$product)
        {
            return response()->json([
                "message" => "Product Not found"
            ]);
        }

        return response()->json([
            "proudct" => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(ProductRequest $request, $id)
    // {
    //     try{
    //         $product = Product::findOrFail($id);

    //         if(!$product)
    //         {
    //             return response()->json([
    //                 "message" => "Product Not Found"
    //             ],404);
    //         }

    //         $product->name = $request->name;
    //         $product->description = $request->description;

    //         if($request->image)
    //         {
    //             //public path of image
    //             $storage = Storage::disk('public');

    //             //delete old image
    //             if($storage->exists($product->image))
    //                 $storage->delete($product->image);

    //             //image name
    //             $fileName = Str::random(32).".".$request->image->getClientOriginalExtension();
    //             $product->image = $fileName;

    //             //image store in public path
    //             $storage->put($fileName, file_get_contents($request->image));
    //         }

    //         //update Product
    //         $product->save();

    //         return response()->json([
    //             "message" => "Data Updata Successfully"
    //         ],200);

    //     }catch(\Exception $e){
    //         return response()->json([
    //             "message" => "Data Updata Failed"
    //         ],500);
    //     }
    // }




    public function update(ProductRequest $request, $id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);

            if (!$product) {
                return response()->json([
                    "message" => "Product Not Found"
                ], 404);
            }

            // Update product information
            $product->name = $request->name;
            $product->description = $request->description;

            // Handle product image
            if ($request->hasFile('image')) {
                $storage = Storage::disk('public');

                // Delete old image if it exists
                if ($storage->exists($product->image)) {
                    $storage->delete($product->image);
                }

                // Generate a random filename
                $fileName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
                $product->image = $fileName;

                // Store the new image
                $storage->put($fileName, file_get_contents($request->file('image')));
            }

            // Save the updated product
            $product->save();

            return response()->json([
                "message" => "Data Updated Successfully"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Data Update Failed",
                "error" => $e->getMessage() // Include the error message for debugging
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $storage = Storage::disk('public');

        if($storage->exists($product->image))
        $storage->delete($product->image);

        $product->delete();

        return response()->json([
            "message" => "Data Deleted Successfully",

        ], 200);
    }
}
