<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5 col-md-6">
        <h2 class="text-center mb-4">Change Password</h2>

        <form action="{{ route('change_password.form') }}" method="POST" id="changePasswordForm" class="bg-white p-4 rounded shadow-sm">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password:</label>
                <input type="password" name="current_password" id="current_password" class="form-control">
                @error('current_password')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" name="new_password" id="new_password" class="form-control">
                @error('new_password')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirm New Password:</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                @error('new_password_confirmation')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </form>

        <div class="mt-3">
            <x-back-button />
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#changePasswordForm').on('submit', function (e) {
                let isValid = true;
                const currentPassword = $('#current_password').val().trim();
                const newPassword = $('#new_password').val().trim();
                const confirmPassword = $('#new_password_confirmation').val().trim();
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])/;

                if (!currentPassword || !newPassword || !confirmPassword) {
                    alert('All fields are required.');
                    isValid = false;
                } 
                else if (newPassword.length < 5) {
                    alert('New password must be at least 5 characters long.');
                    isValid = false;
                } 
                else if (!passwordRegex.test(newPassword)) {
                    alert("New password must contain at least 1 capital letter, 1 small letter, 1 number, and 1 special character.");
                    isValid = false;
                } 
                else if (newPassword !== confirmPassword) {
                    alert('Passwords do not match.');
                    isValid = false;
                }

                return isValid;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
