<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/C.png">

    <title>Iniciar sesión - Clyser</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>
    <h1>Bienvenido a Clyser</h1>
    <h2>Iniciar sesión</h2>

    <?php if (isset($_GET['error'])): ?>
    <p style="color:red;">
        <?php 
        switch($_GET['error']) {
            case 'correo_existente': echo " Este correo ya está registrado."; break;
            case 'usuario_no_encontrado': echo " No existe una cuenta con ese correo."; break;
            case 'password_incorrecto': echo " La contraseña es incorrecta."; break;
            default: echo " Ocurrió un error.";
        }
        ?>
    </p>
<?php endif; ?>


    <form method="POST" action="apps/Controlador/UsuarioControlador.php">
        <input type="hidden" name="accion" value="login">

        <label>Correo electrónico:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>



    <hr>

    <p>¿No tienes cuenta?</p>
    <a href="apps/Vista/registroCliente.php">Registrarme como Cliente</a> |
    <a href="apps/Vista/registroProveedor.php">Registrarme como Proveedor</a>
</body>
</html>
