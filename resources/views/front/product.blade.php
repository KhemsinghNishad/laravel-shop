@extends('front.layouts.app')
<style>
    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ccc;
        padding: 0 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .star-rating input:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: gold;
    }
</style>
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>

                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col-md-5">

                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">
                            @if ($product->product_image)
                                @foreach ($product->product_image as $key => $productImage)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img class="w-100 h-100"
                                            src="{{ asset('uploads/product/large/' . $productImage->image) }}"
                                            alt="Image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>

                </div>
                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="text-primary mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star-half-alt"></small>
                                <small class="far fa-star"></small>
                            </div>
                            <small class="pt-1">({{ $product->ratings_count }} Reviews)</small>
                        </div>
                        <h2 class="price ">{{ $product->price }}</h2>
                        @if ($product->compare_price > 0)
                            <h2 class="price text-secondary"><del>{{ $product->compare_price }}</del></h2>
                        @endif


                        <p>{!! $product->short_description !!}</p>
                        <a href="javascript:void(0);" onclick="addToCart({{ $product->id }})" class="btn btn-dark"><i
                                class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                    type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping &
                                    Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                <p>
                                    {!! $product->description !!}
                                </p>
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                <p>{!! $product->shipping_returns !!}
                                </p>
                            </div>
                            <!-- Reviews tab pane -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">

                                @if (auth()->check())
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    <form action="{{ route('rate.product') }}" method="POST"
                                        class="border p-3 rounded mt-4 bg-white" novalidate>
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <h5 class="mb-3">Rate this product</h5>

                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label class="form-label">Your Name</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', auth()->user()->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label class="form-label">Your Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- STAR RATING -->
                                        <div class="mb-3">
                                            <label class="form-label d-block">Your Rating</label>

                                            <div class="star-rating d-flex flex-row-reverse justify-content-start mb-1">
                                                <input type="radio" name="rating" id="rating-5" value="5"
                                                    {{ old('rating') == '5' ? 'checked' : '' }}>
                                                <label for="rating-5"><i class="bi bi-star-fill"></i></label>

                                                <input type="radio" name="rating" id="rating-4" value="4"
                                                    {{ old('rating') == '4' ? 'checked' : '' }}>
                                                <label for="rating-4"><i class="bi bi-star-fill"></i></label>

                                                <input type="radio" name="rating" id="rating-3" value="3"
                                                    {{ old('rating') == '3' ? 'checked' : '' }}>
                                                <label for="rating-3"><i class="bi bi-star-fill"></i></label>

                                                <input type="radio" name="rating" id="rating-2" value="2"
                                                    {{ old('rating') == '2' ? 'checked' : '' }}>
                                                <label for="rating-2"><i class="bi bi-star-fill"></i></label>

                                                <input type="radio" name="rating" id="rating-1" value="1"
                                                    {{ old('rating') == '1' ? 'checked' : '' }}>
                                                <label for="rating-1"><i class="bi bi-star-fill"></i></label>
                                            </div>

                                            @error('rating')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Review Text -->
                                        <div class="mb-3">
                                            <label class="form-label">Write your review</label>
                                            <textarea name="review" class="form-control @error('review') is-invalid @enderror" rows="3" required>{{ old('review') }}</textarea>
                                            @error('review')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                @else
                                    <p class="text-danger mt-3">Please login to submit your review.</p>
                                    <a href="{{ route('user.login') }}" class="btn btn-primary">Login</a>
                                @endif

                                <h5 class="mt-4 mb-3">Customer Reviews</h5>                               

                                {{-- Approved reviews listing --}}
                                @forelse($product->ratings as $rating)
                                    <div class="border p-3 mb-2 bg-white rounded">
                                        <strong>{{ $rating->name }}</strong>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="bi {{ $i <= $rating->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0">{{ $rating->review }}</p>
                                    </div>
                                @empty
                                    <p>No reviews yet.</p>
                                @endforelse


                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-5 section-8">
        <div class="container">
            <div class="section-title">
                <h2>Related Products</h2>
            </div>
            {{-- <div class="col-md-12">
                <div id="related-products" class="carousel">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="" class="product-img"><img class="card-img-top" src="images/product-1.jpg"
                                    alt=""></a>
                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                            <div class="product-action">
                                <a class="btn btn-dark" href="#">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                            </div>
                        </div>
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="">Dummy Product Title</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>$100</strong></span>
                                <span class="h6 text-underline"><del>$120</del></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                @if (!empty($relatedProducts))
                    @foreach ($relatedProducts as $related_product)
                        @php
                            $productImage = $related_product->product_image->first();
                        @endphp
                        <div class="col-md-3 mb-4">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="" class="product-img">
                                        <img class="card-img-top"
                                            src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                            alt="image ">
                                    </a>
                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>
                                    <div class="product-action">
                                        <a class="btn btn-dark" href="javascript:void(0);"
                                            onclick="addToCart({{ $product->id }})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $related_product->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>{{ $related_product->price }}</strong></span>
                                        @if ($related_product->compare_price > 0)
                                            <span
                                                class="h6 text-underline"><del>{{ $related_product->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


        </div>
    </section>
@endsection

@section('customJs')
    <script></script>
@endsection
