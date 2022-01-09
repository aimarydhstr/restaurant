<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderTableResource;
use Validator;
use App\Models\OrderTable;

class OrderTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = OrderTable::latest()->get();

        $ordertables = OrderTableResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'OrderTables List', 
            'data' => $ordertables
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
            'table_id' => 'required',
            'customer_id' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = OrderTable::create([
            'table_id' => $request->table_id,
            'customer_id' => $request->customer_id,
            'status' => 1,
        ]);

        $order = Order::where('customer_id', $request->customer_id)->where('status', 1)->first();
        if (!empty($order)) {
            $table_id = $request->table_id;
            $add_table = $order->update([
                'table_id' => $table_id,
            ]);
        }

        $res = new OrderTableResource($data);

        return response()->json([
            'status' => true,
            'message' => 'Table Ordered',
            'data' => $res
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
        $data = OrderTable::findOrFail($id);
        $res = new OrderTableResource($data);
        $ordertables = $data->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'OrderTable updated',
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
        $data = OrderTable::findOrFail($id);
        $ordertables = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'OrderTable #'.$data->id.' deleted',
        ], 200);
    }
}
