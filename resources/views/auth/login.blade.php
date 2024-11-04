<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container mt-5 col-md-6">
            <h2 class="text-center mb-4">Login</h2>

            <form action="{{ route('login') }}" method="POST" id="login" class="bg-white p-4 rounded shadow-sm">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    @error('email')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control">
                    @error('password')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <a href="{{ route('forgetPassword') }}" id="forget_password" name="forget_password" style="text-decoration: none">Forget Password</a>
                </div>



                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <div class="mt-3">
                <x-back-button />
            </div>
        </div>





        <script>
            function PasswordValidator() {
                var passwordvalid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])$/;
                var passwordstrength = document.getElementById("<%=txtPassword.ClientID%>");
                if (passwordstrength != passwordvalid) {
                    alert("Password must contain at least 1 capital letter,\n\n1 small letter, 1 number and 1 special character.\n\nFor special characters you can pick one of these -,(,!,@,#,$,),%,<,>");
                    return false;
                }
            }


            $(document).ready(function() {
                $('#login').validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            max: 5

                        },

                    },
                    messages: {

                        email: {
                            required: 'Email is a required field.',
                            email: 'Please enter a valid email address.'
                        },
                        password: {
                            required: 'Password is a required field.',
                            max: 'invalid length'
                        }
                    }
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>