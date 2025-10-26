<?php

namespace App\Http\Controllers;

use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ShippingChargeController extends Controller
{
    public function create(){
        $shippingDetails = ShippingCharge::all();
        $data['shippingDetails'] = $shippingDetails;
        return view('admin.shipping.create', $data);
    }

    public function store(Request $request){
        // Validate the incoming request data

        $validator = Validator::make($request->all(), [
            'country' => 'required|string|max:255',
            'shipping_charge' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'Validation failed. Please check your input.');
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        // Create a new ShippingCharge record
        $shippingCharge = new \App\Models\ShippingCharge();
        $shippingCharge->country_id = $request->input('country');
        $shippingCharge->amount = $request->input('shipping_charge');
        $shippingCharge->save();

        return response()->json(['status' => true, 'message' => 'Shipping charge added successfully.']);
    }
}
