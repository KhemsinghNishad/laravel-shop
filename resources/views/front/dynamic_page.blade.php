@extends('front/layouts/app')

@section('content')
<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Page Title --}}
                    <h2 class="mb-3">{{ $page->name }}</h2>
                    <hr>

                    {{-- Page Content --}}
                    @if ($page->content)
                        <div class="mt-3">
                            {!! $page->content !!}
                        </div>
                    @else
                        <p class="text-muted mt-3">This page is has no content</p>
                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
