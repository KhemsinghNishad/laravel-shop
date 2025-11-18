@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <form action="" name="orderForm" id="orderForm">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                {{-- @foreach ($countries as $country)
                                                    <option value="">{{ $country['name']['common'] }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control"></textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="appartment" id="appartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                                placeholder="State">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No.">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ $item->qty * $item->price }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    @if (session()->has('discountCode'))
                                        <div class="h6"><strong
                                                class="subtotal">${{ session('newSubtotal') }}</strong></div>
                                    @else
                                        <div class="h6"><strong class="subtotal">${{ Cart::subtotal() }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong>$0</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Discount</strong></div>
                                    @if (session()->has('discountCode'))
                                        <div class="h6"><strong
                                                class="discount">${{ session('newDiscount') }}</strong></div>
                                    @else
                                        <div class="h6"><strong class="discount">$0</strong></div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    @if (session()->has('discountCode'))
                                        <div class="h5"><strong
                                                class="subtotal">${{ session('newSubtotal') }}</strong></div>
                                    @else
                                        <div class="h5"><strong class="subtotal">${{ Cart::subtotal() }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control">
                            <button class="btn btn-dark" type="button" id="apply_coupon_btn">Apply Coupon</button>
                        </div>
                        @if (session()->has('discountCode'))
                            <div id="appliedCouponWrapper" class="mt-3 d-flex align-items-center">
                                                <span id="appliedCouponCode" class="me-2 badge bg-secondary">
                                                    {{ session('discountCode') }}
                                                </span>

                                                <button class="removeAppliedCoupon" class="btn btn-danger btn-sm" type="button" style="padding:2px 6px;">
                                                    ✕
                                                </button>
                                            </div>
                        @endif                                            
                        <div class="card payment-form ">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                            <div>
                                <input checked type="radio" name="payment_method" id="payment_method_one"
                                    value="cod">
                                <label for="payment_method_one">COD</label>
                            </div>
                            <div>
                                <input type="radio" name="payment_method" id="payment_method_two" value="stripe">
                                <label for="payment_method_two">STRIPE</label>
                            </div>
                            <div class="card-body p-0 mt-3 d-none" id="payment_form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="pt-4">
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>

                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('customJs')
    <script>
        $('#payment_method_one').click(function() {
            if ($(this).is(":checked") == true) {
                $('#payment_form').addClass('d-none')
            }
        });
        $('#payment_method_two').click(function() {
            if ($(this).is(":checked") == true) {
                $('#payment_form').removeClass('d-none')
            }
        });

        $('#orderForm').submit(function(event) {
            event.preventDefault();
            $formData = $(this).serialize();
            $('button[type = "submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('checkout.process') }}',
                type: 'POST',
                data: $formData,
                dataType: 'json',
                success: function(response) {
                    $('button[type = "submit"]').prop('disabled', false);

                    let error = response.errors;


                    $('#first_name').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#last_name').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#email').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#address').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#city').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#state').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#zip').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    $('#mobile').removeClass("is-invalid").siblings("p").removeClass(
                        'invalid-feedback').html();

                    if (response.status == true) {
                        var user_name = response.user_name;
                        var order_id = response.order_id;

                        var url = "{{ route('hello', [':user_name', ':orderId']) }}"
                            .replace(':user_name', user_name)
                            .replace(':orderId', order_id);

                        window.location.href = url;
                    }
                    if (response.status == false) {
                        if (error.first_name) {
                            $('#first_name').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.first_name);
                        }
                        if (error.last_name) {
                            $('#last_name').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.last_name);
                        }
                        if (error.email) {
                            $('#email').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.email);
                        }
                        if (error.address) {
                            $('#address').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.address);
                        }
                        if (error.city) {
                            $('#city').addClass("is-invalid").siblings("p").addClass('invalid-feedback')
                                .html(error.city);
                        }
                        if (error.state) {
                            $('#state').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.state);
                        }
                        if (error.zip) {
                            $('#zip').addClass("is-invalid").siblings("p").addClass('invalid-feedback')
                                .html(error.zip);
                        }
                        if (error.mobile) {
                            $('#mobile').addClass("is-invalid").siblings("p").addClass(
                                'invalid-feedback').html(error.mobile);
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("error");
                }
            });
        });


        $('#apply_coupon_btn').click(function() {
            var coupon_code = $(this).prev().val();
            if (coupon_code == '') {
                alert('Please enter a coupon code');
                return false;
            }

            $.ajax({
                url: '{{ route('apply.coupon') }}',
                type: 'post',
                data: {
                    'coupon_code': coupon_code
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        // var variable = `<div id="appliedCouponWrapper" class="mt-3">
                    //                     <button type="button" class="btn btn-secondary d-flex align-items-center">
                    //                         <span id="appliedCouponCode" class="me-2">${response.coupon_code.code}</span>
                    //                         <button id="removeAppliedCoupon" class="btn btn-danger btn-sm" type = "button" style="padding:2px 6px;">
                    //                             ✕
                    //                         </button>

                    //                     </button>
                    //                 </div>`;
                        var variable = `<div id="appliedCouponWrapper" class="mt-3 d-flex align-items-center">
                                                <span id="appliedCouponCode" class="me-2 badge bg-secondary">
                                                    ${response.coupon_code.code}
                                                </span>

                                                <button class="removeAppliedCoupon" class="btn btn-danger btn-sm" type="button" style="padding:2px 6px;">
                                                    ✕
                                                </button>
                                            </div>`;

                        $('.apply-coupan').after(variable);
                        $('.discount').text('$' + response.coupon_code.discount_amount);
                        $('.subtotal').text('$' + response.newSubtotal);

                        alert(response.message);

                    } else {
                        // $('.subtotal').html('0');
                        alert(response.message);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        $(document).on('click', '.removeAppliedCoupon', function() {
            $.ajax({
                url: '{{ route('remove.coupon') }}',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $('#appliedCouponWrapper').remove();
                        $('.discount').text('$0');
                        $('.subtotal').text('$' + response.subtotal);
                        alert(response.message);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log(jqXHR.responseText);
                }
            });
        });
    </script>
@endsection
