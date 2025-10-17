@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Category</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('categories.list') }} " class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form method="POST" id="categoryForm" name="categoryForm">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name" value="{{ $category->name }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control"
                                            placeholder="Slug" value="{{ $category->slug }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="textId" name="imageId" value="">
                                        <label for="image">Image</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload. <br><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($category->image))
                                    <div>
                                        <img height="250" width="250"
                                            src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt="image">
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="select">Status</label>
                                        <select name="status" id="select" class="form-control">
                                            <option {{ $category->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $category->status == 0 ? 'selected' : '' }} value="0">Block
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="show_home">Show in home page</label>
                                        <select name="showHome" id="show_home" class="form-control">
                                            <option {{ $category->showHome == 'Yes' ? 'selected' : '' }} value="Yes">Yes</option>
                                            <option {{ $category->showHome == 'No' ? 'selected' : '' }} value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('categories.list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#categoryForm').submit(function(event) {
            event.preventDefault();
            var element = $(this).serialize();

            $.ajax({
                url: '{{ route('categories.update', $category->id) }}',
                type: 'PUT',
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

                        if (response.notFound == true) {
                            window.location.href = '{{ route('categories.list') }}'
                        }
                        // Handle success (e.g., show a message or reset the form)
                        // alert('Category added successfully!');
                        $('#categoryForm')[0].reset();
                        window.location.href = '{{ route('categories.list') }}'
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText); // useful for debugging
                }
            });
        });




        // AJAX for slug generation
        $('#name').change(function() {
            var element = $(this);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'GET',
                data: {
                    title: element.val()
                },
                dataType: 'json', // ✅ FIXED: 'json' must be a string
                success: function(response) {
                    if (response.status === true) {
                        $('#slug').val(response.slug);
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
            url: "{{ route('temp.image.create') }}",
            paramName: 'image',
            maxFiles: 1,
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            success: function(file, response) {
                $('#textId').val(response.image_id);
            }
        });
    </script>
@endsection
