<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type }} Attendance Recorded</title>
    <link href ="{{ asset('assets/style/email-template.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>{{ $type }} Attendance Recorded</h1>
        <p>Dear <strong>{{ $employeeName }}</strong>,</p>

        <p>Your {{ strtolower($type) }} has been successfully recorded at
            <strong>{{ $recordedTime }}</strong>. Thank you for your attendance!
        </p>

        <a href="{{ url('/') }}">Visit Our Website</a>
    </div>
</body>

</html>
