@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Product</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('product.list') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <form action="" method="POST" id="productForm" name="Media">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                    placeholder="Title" value="{{ $product->title }}">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="slug">Slug</label>
                                                <input type="text" readonly name="slug" id="slug"
                                                    class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                    placeholder="Description">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="short_description">Short Description</label>
                                                <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote">{{ $product->short_description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="shipping_returns">Shipping And Returns</label>
                                                <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote">{{ $product->shipping_returns }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Media</h2>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="product-gallary"></div>
                            @if ($productImage)
                                @foreach ($productImage as $image)
                                    <div class="col-md-3" id="image-row-{{ $image->id }}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{ $image->id }}}">
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $image->image) }}"
                                                alt="Card image cap">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onclick="DeleteImage({{ $image->id }})"
                                                    class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Pricing</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="price">Price</label>
                                                <input type="number" name="price" id="price" class="form-control"
                                                    placeholder="Price" value="{{ $product->price }}">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="compare_price">Compare at Price</label>
                                                <input type="text" name="compare_price" id="compare_price"
                                                    class="form-control" placeholder="Compare Price"
                                                    value="{{ $product->compare_price }}">
                                                <p class="text-muted mt-3">
                                                    To show a reduced price, move the product’s original price into Compare
                                                    at price. Enter a lower value into Price.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Inventory</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku">SKU (Stock Keeping Unit)</label>
                                                <input type="text" name="sku" id="sku" class="form-control"
                                                    placeholder="sku" value="{{ $product->sku }}">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="barcode">Barcode</label>
                                                <input type="text" name="barcode" id="barcode" class="form-control"
                                                    placeholder="Barcode" value="{{ $product->barcode }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="hidden" name="track_qty" value="No">
                                                    <input class="custom-control-input" type="checkbox" value="Yes"
                                                        id="track_qty" name="track_qty"
                                                        {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                                    <p class="error"></p>
                                                    <label for="track_qty" class="custom-control-label">Track
                                                        Quantity</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <input type="number" min="0" name="qty" id="qty"
                                                    class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Product status</h2>
                                    <div class="mb-3">
                                        <select name="status" id="status" class="form-control">
                                            <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Block
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="h4  mb-3">Product category</h2>
                                    <div class="mb-3">
                                        <label for="category">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Selct your category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option {{ $category->id == $product->category_id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No category</option>
                                            @endif
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category">Sub category</label>
                                        <select name="sub_category" id="sub_category" class="form-control">
                                            <option value="">Select sub category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Product brand</h2>
                                    <div class="mb-3">
                                        <select name="brand" id="brand" class="form-control">
                                            <option value="">Select brand</option>
                                            @if ($brands->isNotEmpty())
                                                {
                                                @foreach ($brands as $brand)
                                                    <option {{ $brand->id == $product->brand_id ? 'selected' : '' }}
                                                        value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                                }
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Featured product</h2>
                                    <div class="mb-3">
                                        <select name="is_featured" id="is_featured" class="form-control">
                                            <option {{ $product->is_featured == 'No' ? 'selected' : '' }} value="No">
                                                No</option>
                                            <option {{ $product->is_featured == 'Yes' ? 'selected' : '' }} value="Yes">
                                                Yes</option>
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Related product</h2>
                                    <div class="mb-3">
                                        <select multiple class="related-products w-100" name="related_products[]"
                                            id="related_products">
                                            @if (!empty($relatedProducts))
                                                @foreach ($relatedProducts as $relProduct)
                                                    <option selected value="{{ $relProduct->id }}">
                                                        {{ $relProduct->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('product.list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </div>
            </form>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('customJs')
    <script>
        $('.related-products').select2({
            ajax: {

                url: '{{ route('product.get-product') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function(data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });
        $('#title').change(function() {
            var element = $(this);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val(),
                },
                dataType: 'json',
                success: function(response) {
                    $('#slug').val(response.slug);
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });

        $('#category').change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route('product.subcategory') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#sub_category').find('option').not(':first').remove();
                    $.each(response.subcategories, function(key, item) {
                        $('#sub_category').append(
                            `<option value="${item.id}">${item.name}</option>`)
                    });
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });

        $('#productForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serializeArray();
            $('input[type="button"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('product.update', $product->id) }}',
                type: 'put',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('input[type="button"]').prop('disabled', true);
                    var errors = response.errors;
                    if (response.status == true) {
                        window.location.href = "{{ route('product.list') }}";
                    } else {
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"], select , input[type="number"]').removeClass(
                            'is-invalid');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback').html(value);
                        });
                    }

                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });

        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone('#image', {
            url: "{{ route('product.image.update') }}",
            paramName: 'image',
            params: {
                'product_id': '{{ $product->id }}'
            },
            maxFiles: 10,
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(file, response) {
                // $('#textId').val(response.image_id);
                var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                <input type="hidden" name="image_array[]" value="${response.image_id}">
                <img class="card-img-top" src="${response.image_path}" alt="Card image cap">
                <div class="card-body">
                    <a href="javascript:void(0)" onclick="DeleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                </div>
                </div></div>`;

                $('#product-gallary').append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function DeleteImage(id) {
            $('#image-row-' + id).remove();
            if (confirm("Are you sure you want to delete ? ")) {
                $.ajax({
                    url: '{{ route('product.image.delete') }}',
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("Something went wrong");
                        console.log(jqXHR.responseText);
                    }
                });
            }
        }
    </script>
@endsection
