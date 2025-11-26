<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>
<body>
  <h4>Dear {{ $mailData['user']->name }}</h4>
<p>You have requested to reset your password. Please click the link below to reset your password:</p>

<p>
    <a href="{{ route('user.reset-password-form', ['token' => $mailData['token']]) }}">
        Reset Password
    </a>
</p>

<p>If you did not request a password reset, please ignore this email.</p>
<p>Thank you!</p>

</body>
</html>