<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function totalEarning(Request $request)
    {
        $orders = [];
        if(count($request->all()) > 0){
            $orders = Order::whereBetween('created_at',[$request->start_date,$request->end_date])->get()->each(function($order){
                $order->orderDetail->each(function($orderDetail) use($order){
                    $order->courses .= $orderDetail->course->name . ',';
                });
            });
            session()->flash('start_date',$request->start_date);
            session()->flash('end_date',$request->end_date);
        }
        return view('admin.reports.total-earing',compact('orders'));
    }
}
