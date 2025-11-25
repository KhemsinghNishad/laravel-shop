@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create User</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('user.list') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <form action="{{ route('user.store') }}" method="POST">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" placeholder="Name"
                                            value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" placeholder="Email"
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror">

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" placeholder="Phone"
                                            value="{{ old('phone') }}"
                                            class="form-control @error('phone') is-invalid @enderror">

                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password"
                                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            placeholder="Enter Password">

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Address REMOVED -->

                            </div>
                        </div>
                    </div>

                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary">Create</button>
                        <a href="{{ route('user.list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>

                </form>

            </div>
        </section>

    </div>
@endsection
@section('customJs')
    <script></script>
@endsection
