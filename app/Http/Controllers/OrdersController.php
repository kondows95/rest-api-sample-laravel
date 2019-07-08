<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;
use App\Models\Orderitem;
use App\Http\Requests\Order\StoreOrderRequest;
use Illuminate\Support\Facades\Hash;

class OrdersController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        return \DB::transaction(function() use($request) {
            $data = $request->validated();
            
            //insert order
            $orderKeys = ['total_price', 'first_name', 'last_name', 'address1', 'address2', 'country', 'state', 'city'];
            $orderArr = [];
            foreach ($orderKeys as $key) {
                $orderArr[$key] = $data[$key];
            }
            $orderModel = Order::create($orderArr);
            
            //insert orderitems
            foreach ($data['item_id_array'] as $i => $itemId) {
                $itemArr = [
                    'order_id' => $orderModel->id,
                    'item_id' => $itemId,
                    'unit_price' => $data['item_price_array'][$i],
                    'quantity' => $data['item_qty_array'][$i]
                ];
                $dump[] = Orderitem::create($itemArr);
            }
            
            return new JsonResource($orderModel);
        });
    }
}
