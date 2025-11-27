@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ratings</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('product.list') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('rating.list') }}" class="btn btn-secondary">Reset</a>

                        <div class="card-tools">
                            <form action="{{ route('rating.list') }}" method="GET">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="table_search" value="{{ request('table_search') }}"
                                        class="form-control" placeholder="Search Name/Email/Review">

                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Product</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($ratings as $rating)
                                    <tr>
                                        <td>{{ $rating->id }}</td>
                                        <td>{{ $rating->product->title ?? 'N/A' }}</td>
                                        <td>{{ $rating->name }}</td>
                                        <td>{{ $rating->email }}</td>
                                        <td>
                                            {{ $rating->rating }}
                                        </td>
                                        <td>{{ Str::limit($rating->review, 40) }}</td>
                                        <td>
                                            @if ($rating->status == '1')
                                                <a href="javascript:void(0)" onclick="changeRating('0', {{ $rating->id }})">
                                                    <svg class="text-success-500 h-6 w-6 text-success"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" onclick="changeRating('1', {{ $rating->id }})">
                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No ratings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer clearfix">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content -->
    </div>
@endsection
@section('customJs')
    <script>
        function changeRating(status, rating_id) { 
            var ratingId = rating_id;                  
            var url = '{{ route('rating.approve', 'status') }}';            
            var newUrl = url.replace('status', status);            
            if (confirm('Are you sure you want to change status')) {
                $.ajax({
                    url: newUrl,
                    type: 'put',
                    data: {
                        rating_id: ratingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ route('rating.list') }}"
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("Something went wrong");
                        console.log(jqXHR.responseText);
                    }
                });
            }
        }
    </script>
@endsection
