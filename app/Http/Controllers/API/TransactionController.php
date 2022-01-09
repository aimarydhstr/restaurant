<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use Validator;
use App\Models\Order;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Transaction::latest()->get();

        $transactions = TransactionResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'All Transactions List', 
            'data' => $transactions
        ], 200);
    }

    public function offline()
    {
        $data = Transaction::where('mode', 'offline')->latest()->get();

        if (!empty($data)) {
            $transactions = TransactionResource::collection($data);
            
            return response()->json([
                'status' => true, 
                'message' => 'Offline Transactions List', 
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => true, 
                'message' => 'Offline Transactions List', 
                'data' => 'Offline Transactions Not Found'
            ], 404);
        }
    }

    public function holdon()
    {
        $data = Transaction::where('mode', 'holdon')->latest()->get();

        if (!empty($data)) {
            $transactions = TransactionResource::collection($data);
            
            return response()->json([
                'status' => true, 
                'message' => 'Hold On Transactions List', 
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => true, 
                'message' => 'Hold On Transactions List', 
                'data' => 'Hold On Transactions Not Found'
            ], 404);
        }
    }

    public function earning()
    {
        $data = Transaction::all();
        $total = 0;

        foreach ($data as $t) {
            $total += $t->total;
        }
        
        return response()->json([
            'status' => true, 
            'message' => 'All Transactions List', 
            'data' => 'All Earning : '.$total
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
            'order_id' => 'required',
            'type' => 'required',
            'mode' => 'required',
            'cash' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::where('status', 1)->where('id', $request->order_id)->first();
        $table_id = NULL;

        $note = $request->note;

        if($note == NULL) {
            $note = 'NA';
        }

        if(!empty($order)) {

            if ($request->cash >= $request->total) {

                if ($order->table_id != NULL) {
                    $table_id = $table->table_id;
                }

                $balance = $request->cash - $order->total;

                $data = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $request->customer_id,
                    'order_id' => $request->order_id,
                    'table_id' => $table_id,
                    'type' => $request->type,
                    'mode' => $request->mode,
                    'total' => $order->total,
                    'cash' => $request->cash,
                    'balance' => $balance,
                    'status' => 1,
                    'note' => $note,
                ]);

                if ($data) {
                    $up = $order->update([
                        'status' => 0
                    ]);
                }

                $res = new TransactionResource($data);
                
                return response()->json([
                    'status' => true,
                    'message' => 'Transaction Successfully',
                    'data' => $res
                ], 201);

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction Failed',
                    'data' => 'Not enough cash'
                ], 422);
            }

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed',
                'data' => 'Order Unavalaible'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Transaction::findOrFail($id);

        if (!empty($data)) {
            $transactions = TransactionResource::collection($data);
            
            return response()->json([
                'status' => true, 
                'message' => 'Transactions ID #'.$transactions->id, 
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => true, 
                'message' => 'Transactions Detail', 
                'data' => 'Transactions Not Found'
            ], 404);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Transaction::findOrFail($id);
        $transactions = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaction #'.$data->id.' deleted',
        ], 200);
    }
}
