@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-11">
        <div class="container mt-5">
            <div class="row">
                @include('front.layouts.sidebar')

                <div class="col-md-9">

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Update Account Details
                        </div>

                        <div class="card-body">
                            <form action="{{ route('account.update') }}" method="POST">
                                @csrf

                                {{-- Full Name --}}
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        class="form-control @error('name') is-invalid @enderror">

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                        class="form-control @error('email') is-invalid @enderror">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                        class="form-control @error('phone') is-invalid @enderror">

                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $userDatails->address) }}</textarea>

                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Update Details</button>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
