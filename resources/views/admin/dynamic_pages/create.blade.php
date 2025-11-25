@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Page</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('dynamic-pages.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('dynamic-pages.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">

                <!-- Name -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Name">

                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Slug -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" 
                               readonly 
                               name="slug" 
                               id="slug"
                               value="{{ old('slug') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="Slug">

                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" 
                                id="status" 
                                class="form-control @error('status') is-invalid @enderror">

                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>

                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Content -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="content">Content</label>
                        <textarea name="content" 
                                  id="content" 
                                  class="summernote @error('content') is-invalid @enderror"
                                  cols="30" rows="10">{{ old('content') }}</textarea>

                        @error('content')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('dynamic-pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#name').change(function() {
            var data = $(this).val();
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: data,
                },
                datType: 'json',
                success: function(response) {
                    $('#slug').val(response.slug);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        });
    </script>
@endsection
