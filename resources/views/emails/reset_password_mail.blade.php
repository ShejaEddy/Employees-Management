<!-- resources/views/emails/password_changed_mail.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #27ae60;
        }

        p {
            font-size: 16px;
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }

        a:hover {
            background-color: #219d4b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Changed Successfully</h1>
        <p>Your password has been changed successfully. If you did not initiate this change, please contact support.</p>
        <a href="{{ route('home') }}">Visit Our Website</a>
    </div>
</body>
</html>
