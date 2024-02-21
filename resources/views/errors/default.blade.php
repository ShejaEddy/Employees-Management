<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $message }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container bg-light min-h-screen p-5">
        <div class="row mt-5">
            <div class="col-md-12">
                <h1 class="text-center">
                    <i class="fas fa-exclamation-circle ms-2"></i>
                    Error
                </h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <h4 class="text-center text-danger">{{ $message }}</h1>
            </div>
        </div>
    </div>
</body>

</html>
