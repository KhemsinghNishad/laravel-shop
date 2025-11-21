@extends('admin/layouts/app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6 text-right">
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
                    <div class="card-header">
                        <div class="card-title">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('orders.index') }}" method="GET">
                                <div class="input-group input-group" style="width: 250px;">
                                    <input value="{{ request('table_search') ? request('table_search') : '' }}"
                                        type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
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
                                    <th>Orders #</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Date Purchased</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$orders->isEmpty())
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->id }}</a>
                                            </td>
                                            <td>{{ $order->user_name }}</td>
                                            <td>{{ $order->user_email }}</td>
                                            <td>{{ $order->mobile_no }}</td>
                                            <td>
                                                @if ($order->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="badge bg-info">Shipped</span>
                                                @elseif ($order->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>${{ $order->grand_total }}</td>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">No Records Found</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                     <div class="card-footer clearfix">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('customJs')
    <script></script>
@endsection
