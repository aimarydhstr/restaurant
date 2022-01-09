<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Product;
use Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tag::latest()->paginate(10);
        
        return response()->json([
            'status' => true, 
            'message' => 'Tags List', 
            'data' => $data
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
            'slug' => 'required|unique:tags'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $slug = str_replace(' ', '-', strtolower($request->slug));

        $data = Tag::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tag created',
            'data' => $data
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
        $tag = Tag::where('slug', $slug)->first();
        $data = Product::where('tag_id', $tag->id)->get();
        $res = ProductResource::collection($data);

        return response()->json([
            'status' => true,
            'message' => 'Tag '.$tag->name,
            'data' => $res
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
        $data = Tag::findOrFail($id);
        $tags = $data->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Tag updated',
            'data' => $data
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
        $data = Tag::findOrFail($id);
        $tags = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Tag '.$data->name.' deleted',
        ], 200);
    }
}
