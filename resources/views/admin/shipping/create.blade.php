@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shipping management</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form method="POST" id="shippingForm" name="shippingForm">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="country">Countries</label>
                                        <select name="country" id="countrySelect" class="country form-control">
                                            <option value="">Select country</option>
                                            {{-- @if (getCountries())
                                                @foreach (getCountries() as $country)
                                                    <option value="">{{ $country->name }}</option>
                                                @endforeach

                                            @endif --}}
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shipping_charge">Shipping charge</label>
                                        <input type="text" name="shipping_charge" id="shipping_charge"
                                            class="form-control" placeholder="Shipping charge">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-1 pt-1">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card -->

           <div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Shipping Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Country</th>
                                <th scope="col">Price</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shippingDetails as $index => $shippingDetail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $shippingDetail->country_id }}</td>
                                    <td>₹{{ number_format($shippingDetail->amount, 2) }}</td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No shipping details found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
    </section>
    <!-- /.content -->
    </div>
@endsection

@section('customJs')
    <script>
        $('#shippingForm').submit(function() {
            event.preventDefault();
            var element = $(this).serialize();

            $.ajax({
                url: '{{ route('shipping.store') }}',
                type: 'POST',
                data: element,
                dataType: 'json',
                success: function(response) {
                    if (response.errors) {
                        var errors = response.errors;
                        if (errors.country) {
                            $('#country').addClass('is-invalid');
                            $('#country').siblings('p').addClass('invalid-feedback').html(errors
                                .country[0]);
                        } else {
                            $('#country').removeClass('is-invalid');
                            $('#country').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.shipping_charge) {
                            $('#shipping_charge').addClass('is-invalid');
                            $('#shipping_charge').siblings('p').addClass('invalid-feedback').html(errors
                                .shipping_charge[0]);
                        }
                    } else {
                        // Handle success (e.g., show a message or reset the form)
                        // alert('Category added successfully!');
                        $('#shippingForm')[0].reset();
                        window.location.href = '{{ route('shipping.charge.create') }}'
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText); // useful for debugging
                }
            });
        });

        // $('#countrySelect').on('click', function() {
        $.ajax({
            url: "{{ route('getcountries') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                $('.country').html(''); // clear old options

                $.each(response, function(index, item) {
                    $('.country').append(
                        `<option value="${item.id}">${item.name}</option>`);
                });
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
                console.log(jqXHR.responseText);
            }
        });
        // });
    </script>
@endsection
