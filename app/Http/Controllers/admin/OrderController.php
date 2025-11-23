<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as user_name', 'users.email as user_email');


        $search = $request->table_search ?? '';
        if ($search != '') {
            $orders = $orders->where('orders.id', 'like', '%' . $search . '%')
                ->orWhere('users.name', 'like', '%' . $search . '%')
                ->orWhere('users.email', 'like', '%' . $search . '%');
        }

        $orders = $orders->orderBy('orders.id', 'desc')->paginate(10);
        return view('admin/orders/order-list', ['orders' => $orders]);
    }

    public function show($id)
    {
        $orders = Order::leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as user_name')
            ->find($id);
        $orderDetails = OrderItem::where('order_id', $id)->get();
        if (!$orders) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }
        if ($orderDetails->isEmpty()) {
            return redirect()->route('orders.index')->with('error', 'Order details not found');
        }
        return view('orders.index.details', [
            'order' => $orders,
            'orderDetails' => $orderDetails
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'error' => 'Order not found'
            ]);
        }
        $order->status = $request->status;
        $order->save();
        $request->session()->flash('success', 'Order status updated successfully');
        return response()->json([
            'status' => true,
            'success' => 'Order status updated successfully'
        ]);
    }

    public function exportOrders()
    {
        // Logic to export orders (e.g., to CSV or Excel)
        // This is a placeholder for the actual export functionality



        $orders = sendEmail(17);
        
    }
}
