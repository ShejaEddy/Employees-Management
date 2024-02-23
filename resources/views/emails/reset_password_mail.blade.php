<!-- resources/views/emails/password_changed_mail.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully</title>
    <link href ="{{ asset('assets/style/email-template.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Password Changed Successfully</h1>
        <p>Your password has been changed successfully. If you did not initiate this change, please contact support.</p>
        <a href="{{ route('home') }}">Visit Our Website</a>
    </div>
</body>

</html>
