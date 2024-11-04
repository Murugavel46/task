<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5 col-md-6">
        <h2 class="text-center mb-4">Register</h2>
        <form action="{{ route('register') }}" method="POST" id="registerform" class="bg-white p-3 rounded shadow-sm">
            @csrf
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}">
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date of Birth:</label>
                <input type="date" id="date" name="date_of_birth" class="form-control">
            </div>

            <div class="d-grid">
                <input type="submit" value="Register" class="btn btn-primary">
            </div>
        </form>

        <x-back-button />
    </div>

    <script>
        const today = new Date();
        const eighteenYearsAgo = new Date(today.setFullYear(today.getFullYear() - 18));
        const maxDate = eighteenYearsAgo.toISOString().split('T')[0];
        document.getElementById('date').setAttribute('max', maxDate);

        
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        });

        $(document).ready(function () {
            $('#registerform').validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 25,
                        lettersonly: true
                    },
                    last_name: {
                        required: true,
                        lettersonly: true 
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    date_of_birth: {
                        required: true,
                        date: true
                    }
                },
                messages: {
                    first_name: {
                        required: 'First name is a required field.',
                        maxlength: 'First name cannot exceed 25 characters.',
                        lettersonly: 'First name can contain letters only.'
                    },
                    last_name: {
                        required: 'Last name is a required field.',
                        lettersonly: 'Last name can contain letters only.'
                    },
                    email: {
                        required: 'Email is a required field.',
                        email: 'Please enter a valid email address.'
                    },
                    date_of_birth: {
                        required: 'Date of birth is a required field.',
                        date: 'Please enter a valid date.'
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
