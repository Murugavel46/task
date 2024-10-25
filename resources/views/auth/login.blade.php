<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}"><br>

        @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label>Password:</label>
        <input type="password" name="password"><br>

        @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="submit" value="Login">
    </form>


    <x-back-button />

</body>

</html>