@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Brand</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('brands.list') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <form action="" id="createBrandForm">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
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
                                        <input type="text" readonly name="slug" id="slug" class="form-control"
                                            placeholder="Slug">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="brand">Brand</label>
                                        <select name="status" id="brand" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Block</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary">Create</button>
                        <a href="{{ route('brands.list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#createBrandForm').submit(function(event) {
            event.preventDefault();
            var element = $(this).serialize();
            $.ajax({
                url: '{{ route('brands.store') }}',
                type: 'POST',
                data: element,
                dataType: 'json',
                success: function(response) {
                    $('#name').removeClass('is-invalid');
                    $('#name').siblings('p').removeClass('invalid-feedback').html('');
                    $('#slug').removeClass('is-invalid');
                    $('#slug').siblings('p').removeClass('invalid-feedback').html('');
                    if (response.errors) {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid');
                            $('#name').siblings('p').addClass('invalid-feedback').html(errors.name);
                        }
                        if (errors.slug) {
                            $('#slug').addClass('is-invalid');
                            $('#slug').siblings('p').addClass('invalid-feedback').html(errors.slug);
                        }
                    } else {
                        $('#createBrandForm')[0].reset();
                        window.location.href = '{{ route('brands.list') }}'
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });

        $('#name').change(function() {
            var element = $(this);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'GET',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $('#slug').val(response.slug);
                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong");
                    console.log(jqXHR.responseText);
                }
            });
        });
    </script>
@endsection
