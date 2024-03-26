<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Nuestra Empresa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1abc9c;
            color: #222;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
        }
        .credentials {
            padding: 20px;
            border-radius: 5px;
            text-align: left;
            margin-bottom: 20px;
        }
        .message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .credentials h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .credentials ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .credentials ul li {
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            background-color: #1abc9c;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $message->embed(public_path('logo.png')) }}" alt="Logo de la Empresa">
        </div>
        <div class="message">
            <h1>¡Hola {{ $user->person->names }}!</h1>
            <p>Bienvenido a {{ Config::get('app.name') }}. Estamos muy contentos de tenerte a bordo.</p>
        </div>
        <div class="credentials">
            <h3>Tus credenciales de acceso:</h3>
            <ul>
                <li><strong>Usuario:</strong> {{ $user->email }}</li>
                <li><strong>Contraseña:</strong> {{ $user->realPassword }}</li>
            </ul>
        </div>
        <a href="{{ $redirectUrl }}" class="btn">Ir a la Página Principal</a>
    </div>
</body>
</html>
