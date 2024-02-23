<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href ="{{ asset('assets/style/email-template.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <p>Click the following link to reset your password:</p>
        <a href="{{ route('admin.reset.password', ['email' => $email, 'v_token' => $token]) }}">
            Reset Password
        </a>
    </div>
</body>

</html>
