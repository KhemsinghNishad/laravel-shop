@extends('front/layouts/app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>

                    {{-- <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="false" aria-controls="collapseOne">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                style="">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @if ($category->sub_categories->isNotEmpty())
                                                            @foreach ($category->sub_categories as $sub_category)
                                                                <a href=""
                                                                    class="nav-item nav-link">{{ $sub_category->name }}</a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $key => $category)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $key }}">
                                                <button
                                                    class="accordion-button collapsed {{ $categorySelected == $category->id ? 'text-success' : '' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                                                    aria-controls="collapse{{ $key }}">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $key }}"
                                                class="accordion-collapse collapse {{ $categorySelected == $category->id ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $key }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @if ($category->sub_categories->isNotEmpty())
                                                            @foreach ($category->sub_categories as $sub_category)
                                                                <a href="{{ route('shop', [$category->slug, $sub_category->slug]) }}"
                                                                    class="nav-item nav-link {{ $subcategorySelected == $sub_category->id ? 'text-warning' : '' }}">{{ $sub_category->name }}</a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if ($brands->isNotEmpty())
                                @foreach ($brands as $key => $brand)
                                    <div class="form-check mb-2">
                                        <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                            class="form-check-input brand-label" name="brand[]" type="checkbox"
                                            value="{{ $brand->id }}" id="flexCheckDefault-{{ $key }}">
                                        <label class="form-check-label" for="flexCheckDefault-{{ $key }}">
                                            {{ $brand->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input price-filter" type="checkbox" value="0-100" id="price1"
                                    {{ isset($selectedMin) && isset($selectedMax) && $selectedMin == 0 && $selectedMax == 100 ? 'checked' : '' }}>
                                <label class="form-check-label" for="price1">$0 - $100</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input price-filter" type="checkbox" value="100-200" id="price2"
                                    {{ isset($selectedMin) && isset($selectedMax) && $selectedMin == 100 && $selectedMax == 200 ? 'checked' : '' }}>
                                <label class="form-check-label" for="price2">$100 - $200</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input price-filter" type="checkbox" value="200-500" id="price3"
                                    {{ isset($selectedMin) && isset($selectedMax) && $selectedMin == 200 && $selectedMax == 500 ? 'checked' : '' }}>
                                <label class="form-check-label" for="price3">$200 - $500</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input price-filter" type="checkbox" value="500-1000000"
                                    id="price4"
                                    {{ isset($selectedMin) && isset($selectedMax) && $selectedMin == 500 && $selectedMax == 1000000 ? 'checked' : '' }}>
                                <label class="form-check-label" for="price4">$500+</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown">Sorting</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item sort-option" data-sort="latest"
                                                href="#">Latest</a>
                                            <a class="dropdown-item sort-option" data-sort="high" href="#">Price
                                                High</a>
                                            <a class="dropdown-item sort-option" data-sort="low" href="#">Price
                                                Low</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                @php
                                    $productImage = $product->product_image->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            @if ($productImage)
                                                <a href="{{ route('product.shop', $product->slug) }}" class="product-img"><img class="card-img-top"
                                                        src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                                        alt=""></a>
                                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                    width="50">
                                            @endif

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ $product->price }}</strong></span>
                                                @if ($product->compare_price > 0)
                                                    <span class="h6 text-underline"><del>{{ $product->compare_price }}</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="col-md-12 pt-5">
                            {{-- <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1"
                                            aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav> --}}
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $('.brand-label').change(function() {
            apply_filters();
        });



        function apply_filters() {
            var brands = [];
            $('.brand-label').each(function() {
                if ($(this).is(":checked") == true) {
                    brands.push($(this).val());
                }
            });
            // console.log(brands.toString());
            var url = '{{ url()->current() }}?'
            window.location.href = url + '&brand=' + brands.toString();
        }


        $('.price-filter').change(function() {
            if ($(this).is(':checked')) {
                let priceRange = $(this).val();

                let [min, max] = priceRange.split('-');
                var url = '{{ url()->current() }}?'
                window.location.href = url + '&price=' + min.toString() + ',' + max.toString();
            }
        });


        $('.sort-option').click(function(e) {
            e.preventDefault();
            let sortValue = $(this).data('sort');
            let baseUrl = '{{ url()->current() }}';

            // Build new URL
            window.location.href = baseUrl + '?sort=' + sortValue;
        });
    </script>
@endsection
