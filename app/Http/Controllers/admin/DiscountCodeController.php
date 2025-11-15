<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request) {
        $discount_coupons = DiscountCode::latest();
        $table_search = $request['table_search'] ?? "";
        if ($table_search != "") {
            $discount_coupons = $discount_coupons->where('code','like','%'.$table_search.'%');
        }

        $discount_coupons = $discount_coupons->paginate(10);
        $data['discount_coupons'] = $discount_coupons;
        return view('admin.discount_coupon.list', $data);
    }
    public function create()
    {
        return view('admin.discount_coupon.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:discount_codes,code',
            'type' => 'required|in:percentage,fixed',
            'status' => 'required|in:active,inactive',
            'discount_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->passes()) {
            if (!empty($request->end_date) && !empty($request->start_date)) {
                $now = Carbon::now();
                $start_date = Carbon::parse($request->start_date);

                if ($start_date->lte($now)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['start_date' => 'Start date cannot be less than the present date.']
                    ]);
                }
            }

            if (!empty($request->end_date) && !empty($request->start_date)) {
                $end_date = Carbon::parse($request->end_date);
                if ($end_date->lte($start_date)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['end_date' => 'End date must be greater than start date.']
                    ]);
                }
            }

            $discountCode = new DiscountCode();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->most_use = $request->max_use;
            $discountCode->max_user = $request->max_user;
            $discountCode->type = $request->type;
            $discountCode->status = $request->status;
            $discountCode->minimum_amount = $request->minimum_amount;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->start_date = !empty($request->start_date) ? Carbon::parse($request->start_date) : null;
            $discountCode->end_date = !empty($request->end_date) ? Carbon::parse($request->end_date) : null;
            $discountCode->save();

            $request->session()->flash('success', 'Discount code created successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Discount code created successfully.'
            ]);
        }

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(Request $request, $id) {
        $discount_coupons = DiscountCode::findOrFail($id);
        $data['discount_coupons'] = $discount_coupons;
        return view('admin.discount_coupon.edit', $data);
    }

    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|unique:discount_codes,code,' . $id,
        'type' => 'required|in:percentage,fixed',
        'status' => 'required|in:active,inactive',
        'discount_amount' => 'required|numeric|min:0',
    ]);

    if ($validator->passes()) {

        // Start date validation
        if (!empty($request->end_date) && !empty($request->start_date)) {
            $now = Carbon::now();
            $start_date = Carbon::parse($request->start_date);

            if ($start_date->lte($now)) {
                return response()->json([
                    'status' => false,
                    'errors' => ['start_date' => 'Start date cannot be less than the present date.']
                ]);
            }
        }

        // End date validation
        if (!empty($request->end_date) && !empty($request->start_date)) {
            $end_date = Carbon::parse($request->end_date);

            if ($end_date->lte($start_date)) {
                return response()->json([
                    'status' => false,
                    'errors' => ['end_date' => 'End date must be greater than start date.']
                ]);
            }
        }

        // Update Discount Code
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->code = $request->code;
        $discountCode->name = $request->name;
        $discountCode->description = $request->description;
        $discountCode->most_use = $request->max_use;
        $discountCode->max_user = $request->max_user;
        $discountCode->type = $request->type;
        $discountCode->status = $request->status;
        $discountCode->minimum_amount = $request->minimum_amount;
        $discountCode->discount_amount = $request->discount_amount;
        $discountCode->start_date = !empty($request->start_date) ? Carbon::parse($request->start_date) : null;
        $discountCode->end_date = !empty($request->end_date) ? Carbon::parse($request->end_date) : null;
        $discountCode->save();

        $request->session()->flash('success', 'Discount code updated successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Discount code updated successfully.'
        ]);
    }

    // If validation fails
    return response()->json([
        'status' => false,
        'errors' => $validator->errors()
    ]);
}
    public function destroy(Request $request, $id) {
        $discount_coupons = DiscountCode::findOrFail($id);
        $discount_coupons->delete();

        $request->session()->flash('success', 'Discount code deleted successfully.');
        return response()->json([
            'status' => true, 
            'message' => "Discount code deleted successfully."
        ]);
    }
}
