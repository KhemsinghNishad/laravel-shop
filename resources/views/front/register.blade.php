@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                <form action="" id="userRegisterForm" name="userRegisterForm">
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <p></p>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                        <p></p>
                    </div>

                    <div class="form-group">
                        <input type="number" class="form-control" placeholder="Phone" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <p></p>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password"
                            id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                </form>
                <div class="text-center small">Already have an account? <a href="login.php">Login Now</a></div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $('#userRegisterForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serializeArray();
            $('button[type=submit]').prop('disabled', true);
            $.ajax({
                url: '{{ route('user.process-register') }}',
                type: 'post',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    $('#name').removeClass('is-invalid');
                    $('#name').siblings('p').removeClass('invalid-feedback').html('');
                    $('#email').removeClass('is-invalid');
                    $('#email').siblings('p').removeClass('invalid-feedback').html('');
                    $('#password').removeClass('is-invalid');
                    $('#password').siblings('p').removeClass('invalid-feedback').html('');
                    if (response.status == false) {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid');
                            $('#name').siblings('p').addClass('invalid-feedback').html(errors.name);
                        }
                        if (errors.email) {
                            $('#email').addClass('is-invalid');
                            $('#email').siblings('p').addClass('invalid-feedback').html(errors.email);
                        }
                        if (errors.password) {
                            $('#password').addClass('is-invalid');
                            $('#password').siblings('p').addClass('invalid-feedback').html(errors
                                .password);
                        }
                    }

                    if(response.status == true){
                        window.location.href = '{{ route('user.login') }}'
                    }
                },
                error: function(jQHR, exception) {
                    console.log("Something went wrong");
                    console.log(jQHR.responseText);
                }
            });
        });
    </script>
@endsection
