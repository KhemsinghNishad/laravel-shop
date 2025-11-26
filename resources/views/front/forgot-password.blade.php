@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Forgot Password</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                <form action="{{ route('user.forgot-password-email') }}" method="post">
                    @csrf
                    <h4 class="modal-title">Forgot Your Account</h4>
                    <div class="form-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" name="email" placeholder="Email">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg">Forgot Password</button>

                </form>
                <div class="form-group small">
                    <a href="{{ route('user.login') }}" class="forgot-link">Login</a>
                </div>
                <div class="text-center small">Don't have an account? <a href="{{ route('user.register') }}">Sign up</a></div>
            </div>
        </div>
    </section>
@endsection
