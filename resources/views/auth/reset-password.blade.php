<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" id="formCard">
                    <div class="card-header">Admin Reset Password</div>
                    <div class="card-body">
                        <form id="resetPasswordForm" method="POST" action="{{ route('admin.password.reset') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" readonly
                                    required value="{{ $email }}">
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                            </div>
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card d-none" id="successCard"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': false,
                'progressBar': false,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            }

            $('#resetPasswordForm').submit(function(e) {
                e.preventDefault();

                var $this = $(this);

                const submitBtn = $this.find('button[type="submit"]');
                const loading = "Processing...";
                const resetPasswordTxt = "Reset Password";

                if (submitBtn.text() === loading) return;

                submitBtn.text(loading);

                const url = $this.attr("action");

                $.ajax({
                    url,
                    method: "POST",
                    data: $this.serialize(),
                    success: function(response) {
                        const {
                            message
                        } = response;
                        toastr.success(message);
                        $("#successCard").removeClass("d-none");
                        $("#formCard").addClass("d-none");
                    },
                    error: function(response) {
                        const errors = response;
                        toastr.error(errors);
                        submitBtn.text(resetPasswordTxt);
                    }
                })

            });
        });
    </script>
</body>

</html>
