@extends('front.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center py-5">
                    <h2 class="mb-3 text-success fw-bold">Hello, {{ $user_name }} 👋</h2>
                    <p class="lead mb-4">
                        Your order has been placed successfully!
                    </p>
                    <div class="alert alert-info d-inline-block px-4 py-3 rounded-3">
                        <strong>Order ID:</strong> {{ $order_id }}
                    </div>
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4">
                            <i class="bi bi-house-door"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('CustomJs')
<script>
    // You can write custom JS here if needed
    console.log("Hello page loaded for {{ $user_name }} with order ID {{ $order_id }}");
</script>
@endsection
