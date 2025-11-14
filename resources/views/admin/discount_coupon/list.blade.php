@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Categories</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('discount-codes.create') }}" class="btn btn-primary">New Category</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="card">

                    <form action="{{ route('discount-codes.index') }}" method="GET">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="{{ route('discount-codes.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                            </div>
                            <div class="card-tools">
                                <div class="input-group input-group" style="width: 250px;">
                                    <input value="{{ request('table_search') }}" type="text" name="table_search"
                                        class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>



                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th width="100">Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$discount_coupons->isEmpty())
                                    @foreach ($discount_coupons as $discount_coupon)
                                        <tr>
                                            <td>{{ $discount_coupon->id }}</td>
                                            <td>{{ $discount_coupon->name }}</td>
                                            <td>{{ $discount_coupon->code }}</td>
                                            <td>
                                                @if ($discount_coupon->type == 'percent')
                                                    {{ $discount_coupon->discount_amount }}%
                                                @else
                                                    ${{ $discount_coupon->discount_amount }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $discount_coupon->start_date ? \Carbon\Carbon::parse($discount_coupon->start_date)->format('Y-m-d H:i:s') : '' }}
                                            </td>

                                            <td>
                                                {{ $discount_coupon->end_date ? \Carbon\Carbon::parse($discount_coupon->end_date)->format('Y-m-d H:i:s') : '' }}
                                            </td>

                                            <td>
                                                @if ($discount_coupon->status == 'active')
                                                    <svg class="text-success-500 h-6 w-6 text-success"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="">
                                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="#" class="text-danger w-4 h-4 mr-1">
                                                    <svg wire:loading.remove.delay="" wire:target=""
                                                        class="filament-link-icon w-4 h-4 mr-1"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path ath fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Records Found</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $discount_coupons->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('customJs')
    @if (Session::has('message'))
        <script>
            Swal.fire({
                icon: "success",
                title: {!! json_encode(Session::get('message')) !!},
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
    {{-- @if (Session::has('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: {!! json_encode(Session::get('error')) !!},
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif --}}

    <script>
        function deleteCategory(id) {
            var url = '{{ route('categories.destroy', 'id') }}';
            var newUrl = url.replace('id', id);
            // alert(newUrl);
            // return false;
            if (confirm('Are you sure you want to delete')) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ route('discount-codes.index') }}"
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
