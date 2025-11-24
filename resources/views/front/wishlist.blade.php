@extends('front.layouts.app')
@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">My Wishlist</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.layouts.sidebar')
            </div>

            <div class="col-md-9">
                <div class="card">

                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">My Wishlist</h2>
                    </div>

                    <div class="card-body p-4">

                        @forelse ($wishlist as $item)
                            <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                    @php
                                        $productImage = getProductImage($item->product_id)->image;
                                    @endphp
                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4"
                                       href="{{ route('product.shop', $item->product->slug) }}"
                                       style="width: 10rem;">
                                        <img src="{{ asset('uploads/product/small/' . $productImage) }}"
                                             alt="">
                                    </a>

                                    <div class="pt-2">
                                        <h3 class="product-title fs-base mb-2">
                                            <a href="{{ route('product.shop', $item->product->slug) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>

                                        <div class="fs-lg text-accent pt-2">
                                            ₹{{ $item->product->price }}
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                    <a href="{{ route('wishlist.remove', $item->product_id) }}"
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt me-2"></i>Remove
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">No items in wishlist.</p>
                        @endforelse

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
