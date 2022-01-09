<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Validator;
use Auth;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::latest()->get();

        $products = ProductResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'Products List', 
            'data' => $products
        ], 200);
    }

    public function search(Request $request)
    {
        $data = Product::where('name', 'like', '%'.$request->search.'%')->get();
        $products = ProductResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'Products Search : '.$request->search, 
            'data' => $products
        ], 200);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:products',
            'summary' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
            'price' => 'required',
            'discount' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $image = $request->image;

        if($image == NULL){
            $image = 'noimage.png';
        }

        $slug = str_replace(' ', '-', strtolower($request->slug));

        $data = Product::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'slug' => $slug,
            'summary' => $request->summary,
            'category_id' => $request->category_id,
            'tag_id' => $request->tag_id,
            'image' => $image,
            'price' => $request->price,
            'discount' => $request->discount,
            'status' => 1,
        ]);

        $res = new ProductResource($data);

        return response()->json([
            'status' => true,
            'message' => 'Product created',
            'data' => $res
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $data = Product::where('slug', $slug)->first();
        $products = new ProductResource($data); 

        return response()->json([
            'status' => true,
            'message' => 'Detail Product',
            'data' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $res = new ProductResource($data);
        $products = $data->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product updated',
            'data' => $res
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        $products = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product '.$data->name.' deleted',
        ], 200);
    }
}
