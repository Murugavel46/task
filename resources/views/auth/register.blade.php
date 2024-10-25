<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label>First Name:</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}"><br>

        @error('first_name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label>Last Name:</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}"><br>

        @error('last_name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}"><br>

        @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date_of_birth"><br>

        @error('date_of_birth')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror


        <input type="submit" value="Register">

    </form>



    <x-back-button />


    <script>
        const today = new Date();
        const eighteenYearsAgo = new Date(today.setFullYear(today.getFullYear() - 18));

        const maxDate = eighteenYearsAgo.toISOString().split('T')[0];


        const dateInput = document.getElementById('date');
        dateInput.setAttribute('max', maxDate);
    </script>
</body>

</html>