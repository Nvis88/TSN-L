<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h2>Login de Usuario</h2>

    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="usuario@estudio" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <button type="submit">Ingresar</button>
    </form>

    @if ($errors->any())
    <div style="color:red;">{{ $errors->first() }}</div>
    @endif
</body>

</html>