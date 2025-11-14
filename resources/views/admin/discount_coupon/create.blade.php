@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Discount Coupon</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('discount-codes.index') }} " class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form method="POST" id="discountForm" name="discountForm">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code">Code</label>
                                        <input type="text" name="code" id="code" class="form-control"
                                            placeholder="Code">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_use">Max Use</label>
                                        <input type="number" name="max_use" id="max_use" class="form-control"
                                            placeholder="Max Use">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_user">Max User</label>
                                        <input type="number" name="max_user" id="max_user" class="form-control"
                                            placeholder="Max User">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="fixed">Fixed</option>
                                            <option value="percent">Percent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="minimum_amount">Minimum_amount</label>
                                        <input type="number" name="minimum_amount" id="minimum_amount" class="form-control"
                                            placeholder="Minimum_amount">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_amount">Discount amount</label>
                                        <input type="number" name="discount_amount" id="discount_amount"
                                            class="form-control" placeholder="Discount amount">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date">Start Date & Time</label>
                                        <input type="text" id="start_date" name="start_date"
                                            class="form-control datetimepicker" placeholder="Select date & time">
                                            <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date">End Date & Time</label>
                                        <input type="text" id="end_date" name="end_date"
                                            class="form-control datetimepicker" placeholder="Select date & time">
                                            <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                                        <p>
                                        </p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('discount-codes.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('customJs')
    <script>
        $('#discountForm').submit(function(event) {
            event.preventDefault();
            var element = $(this).serialize();

            $.ajax({
                url: '{{ route('discount-codes.store') }}',
                type: 'POST',
                data: element,
                dataType: 'json',
                success: function(response) {
                    if (response.errors) {
                        var errors = response.errors;
                        if (errors.discount_amount) {
                            $('#discount_amount').addClass('is-invalid');
                            $('#discount_amount').siblings('p').addClass('invalid-feedback').html(errors.discount_amount[0]);
                        } else {
                            $('#discount_amount').removeClass('is-invalid');
                            $('#discount_amount').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.code) {
                            $('#code').addClass('is-invalid');
                            $('#code').siblings('p').addClass('invalid-feedback').html(errors.code[0]);
                        } else {
                            $('#code').removeClass('is-invalid');
                            $('#code').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.type) {
                            $('#type').addClass('is-invalid');
                            $('#type').siblings('p').addClass('invalid-feedback').html(errors.type[0]);
                        } else {

                            $('#type').removeClass('is-invalid');
                            $('#type').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.status) {
                            $('#status').addClass('is-invalid');
                            $('#status').siblings('p').addClass('invalid-feedback').html(errors.status[0]);
                        } else {

                            $('#status').removeClass('is-invalid');
                            $('#status').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.start_date) {
                            $('#start_date').addClass('is-invalid');
                            $('#start_date').siblings('p').addClass('invalid-feedback').html(errors.start_date);
                        } else {

                            $('#start_date').removeClass('is-invalid');
                            $('#start_date').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.end_date) {
                            $('#end_date').addClass('is-invalid');
                            $('#end_date').siblings('p').addClass('invalid-feedback').html(errors.end_date);
                        } else {

                            $('#end_date').removeClass('is-invalid');
                            $('#end_date').siblings('p').removeClass('invalid-feedback').html('');
                        }
                    } else {
                        // Handle success (e.g., show a message or reset the form)
                        // alert('discount added successfully!');
                        $('#discountForm')[0].reset();
                        window.location.href = '{{ route('discount-codes.index') }}'
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                    console.log(jqXHR.responseText); // useful for debugging
                }
            });
        });



    </script>
@endsection
