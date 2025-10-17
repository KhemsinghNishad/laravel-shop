@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Sub Category</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('sub-categories.list') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="POST" id="sub_categoryForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            @if (!empty($categories))
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            placeholder="Slug">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">block</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="show_home">Show in home page</label>
                                        <select name="showHome" id="show_home" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('sub-categories.list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('customJs')
    <script>
        $('#sub_categoryForm').submit(function(event) {
            event.preventDefault();
            var element = $(this).serialize();

            $.ajax({
                url: '{{ route('sub-category.store') }}',
                type: 'POST',
                data: element,
                dataType: 'json',
                success: function(response) {
                    if (response.errors) {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid');
                            $('#name').siblings('p').addClass('invalid-feedback').html(errors.name[0]);
                        } else {
                            $('#name').removeClass('is-invalid');
                            $('#name').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.slug) {
                            $('#slug').addClass('is-invalid');
                            $('#slug').siblings('p').addClass('invalid-feedback').html(errors.slug[0]);
                        } else {

                            $('#slug').removeClass('is-invalid');
                            $('#slug').siblings('p').removeClass('invalid-feedback').html('');
                        }
                    } else {
                        // Handle success (e.g., show a message or reset the form)
                        // alert('Category added successfully!');
                        if (response.status == true) {
                            // console.log("done");
                            $('#sub_categoryForm')[0].reset();
                            window.location.href = '{{ route('sub-categories.list') }}'
                        }

                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText); // useful for debugging
                }
            });
        });

        $('#name').change(function() {
            var element = $(this); // Also changed `val` to `var` (correct keyword)
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val() // Corrected here
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $('#slug').val(response.slug);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });
    </script>
@endsection
