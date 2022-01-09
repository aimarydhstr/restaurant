<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderItemResource;
use Validator;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTable;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Order::latest()->get();

        $orders = OrderResource::collection($data);
        
        return response()->json([
            'status' => true, 
            'message' => 'Orders List', 
            'data' => $orders
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
            'customer_id' => 'required',
            'product_id' => 'required',
            'order_id' => 'required',
            'type' => 'required',
            'quantity' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $table = OrderTable::where('status', 1)->where('customer_id', $request->customer_id)->first();
        $table_id = NULL;

        if(!empty($table)) {
            $table_id = $table->id;
        }

        $note = $request->note;

        if($note == NULL) {
            $note = 'NA';
        }

        $product = Product::where('id', $request->product_id)->first();
        $p_price = $product->price;
        $p_discount = $product->discount;

        $qty = $request->quantity;
        $discount = $p_price * $p_discount / 100;
        $disc = $discount * $qty;
        $subtotal = $p_price * $qty;


        $tax = $subtotal * 5 / 100;
        $total = $subtotal - $disc + $tax;

        $order = Order::where('status', 1)->where('id', $request->order_id)->first();
        $o_subtotal = $subtotal;
        $o_discount = $discount;
        
        if (!empty($order)) {
            $o_subtotal = $order->subtotal;
            $o_subtotal =  $o_subtotal + $subtotal;
            $o_discount = $order->discount;
            $o_discount = $o_discount + $disc;
            $o_tax = $o_subtotal * 5 / 100;
            $o_total = $o_subtotal - $o_discount + $o_tax;
            
            $up = $order->update([
                'subtotal' => $o_subtotal,
                'discount' => $o_discount,
                'tax' => $o_tax,
                'total' => $o_total,
            ]);
        } 

        else {
            $data = Order::create([
                'customer_id' => $request->customer_id,
                'table_id' => $table_id,
                'subtotal' => $subtotal,
                'discount' => $disc,
                'tax' => $tax,
                'coupon_id' => $request->coupon_id,
                'total' => $total,
                'status' => 1,
                'note' => 'NA',
            ]);
        }

        $data = OrderItem::create([
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'discount' => $disc,
            'price' => $subtotal,
            'note' => $note,
        ]);

        $res = new OrderItemResource($data);

        return response()->json([
            'status' => true,
            'message' => 'Order Successfully',
            'data' => $res
        ], 201);
    }

    public function coupon(Request $request, $id)
    {
        $data = Order::findOrFail($id);
        $res = new OrderResource($data);

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        $total = $order->subtotal * $coupon->discount / 100;

        $order = $data->update([
            'coupon_id' => $coupon->id,
            'total' => $total,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Coupon code added',
            'data' => $res
        ], 200);
    }

    public function note(Request $request, $id)
    {
        $data = Order::findOrFail($id);
        $res = new OrderResource($data);

        $order = $data->update([
            'note' => $request->note,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order note updated',
            'data' => $res
        ], 200);
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
        $data = OrderItem::findOrFail($id);
        $res = new OrderItemResource($data);
        $orderItems = $data->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'OrderItem '.$res->product->name.' updated',
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
        $data = Order::findOrFail($id);
        $orderItem = OrderItem::where('order_id', $id);
        $orderItems = $orderItem->delete();
        $orders = $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order #'.$data->id.' deleted',
        ], 200);
    }
}
