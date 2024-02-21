<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type }} Attendance Recorded</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #3498db;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        strong {
            color: #3498db;
        }

        a {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ $type }} Attendance Recorded</h1>
        <p>Dear <strong>{{ $employeeName }}</strong>,</p>

        <p>Your {{ strtolower($type) }} has been successfully recorded at
            <strong>{{ $recordedTime }}</strong>. Thank you for your attendance!</p>

        <a href="{{ url('/') }}">Visit Our Website</a>
    </div>
</body>

</html>
