<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <a href="{{ url()->previous() }}">Go back</a>

    <form action="{{ route('auth.register') }}" method="post">
        @csrf
        <label for="">Name: </label>
        <input type="text" name="name">

        @error('name')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <Label>Email:</Label>
        <input type="email" name="email">

        @error('email')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <label for="">Password: </label>
        <input type="password" name="password">

        <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}">Login</a>
</body>
</html>