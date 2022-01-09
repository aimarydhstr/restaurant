<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Table::latest();
        
        $tables = TableResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'Tables List', 
            'data' => $tables
        ], 200);
    }

    public function guest(Request $request)
    {
        $data = Table::where('guest', '=>', $request->guest);
        $tables = TableResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'Table for '.$request->guest.' guest', 
            'data' => $tables
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
            'guest' => 'required'
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

        $data = Table::create([
            'name' => $request->name,
            'guest' => $request->guest,
            'image' => $image
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Table created',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data = Table::findOrFail($id);
        $tables = $data->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Table updated',
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
        $data = Table::findOrFail($id);
        $tables = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Table '.$data->name.' deleted',
        ], 200);
    }
}
