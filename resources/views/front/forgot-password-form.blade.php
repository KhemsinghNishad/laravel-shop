@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Change Password</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-10">
    <div class="container">
        <div class="login-form">

            <form action="{{ route('user.reset-password') }}" method="POST">
                @csrf

                <h4 class="modal-title mb-4">Change Your Password</h4>                
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Address --}}
                {{-- New Password --}}
                <div class="form-group">
                    <input type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="New Password">

                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <input type="password"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm New Password">

                    @error('password_confirmation')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark btn-block btn-lg">
                    Update Password
                </button>

            </form>

        </div>
    </div>
</section>

@endsection
