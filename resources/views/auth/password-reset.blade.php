<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('password.update') }}">
        <input type="text" name="email">
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="password" name="password" placeholder="Passwrod">
        <input type="password" name="password_confirmation" placeholder="Confirm Passwrod">
    </form>
</body>
</html>