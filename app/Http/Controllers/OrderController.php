<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Store a new order
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer' => 'nullable|string',
            'time' => 'required|date',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.total_item_price' => 'required|numeric',
        ]);

        // Create the order
        $order = Order::create([
            'customer' => $data['customer'],
            'time' => $data['time'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
        ]);

        // Create the order items
        foreach ($data['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_item_price' => $item['total_item_price'],
            ]);
        }

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }
    
    public function show($id)
    {
    $order = Order::with('items')->find($id);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    return response()->json($order);
    }


    // Get all orders
    public function index()
    {
        $orders = Order::with('items')->get();
        return response()->json($orders, 200);
    }

    // Update an order
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'customer' => 'nullable|string',
            'time' => 'required|date',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.total_item_price' => 'required|numeric',
        ]);

        $order->update([
            'customer' => $data['customer'],
            'time' => $data['time'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
        ]);

        // Delete old items and add new items
        $order->items()->delete();
        foreach ($data['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_item_price' => $item['total_item_price'],
            ]);
        }

        return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}

