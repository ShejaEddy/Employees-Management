<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/style/pdf-attendance.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Attendance Report</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Employee Name</th>
                    <th>Arrival Time</th>
                    <th>Departure Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance['date'] }}</td>
                        <td>{{ $attendance['name'] }}</td>
                        <td>{{ $attendance['arrival_time'] }}</td>
                        <td>{{ $attendance['departure_time'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total-attendance text-center">Total Employees: {{ count($attendances) }}</div>
    </div>
    <div class="footer text-center">
        <p>&copy; 2024 Employee Management System. All rights reserved.</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
