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

    @include('front.layouts.sidebar')
@endsection
@section('customJs')
    <script>

    </script>
@endsection
