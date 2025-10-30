<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/C.png">

    <title>Registratro proveedor - Clyser</title>
   
     <link rel="stylesheet" href="../../public/css/style.css">

</head>
<body>
    <h1>Registro de proveedor</h1>


    <form method="POST" action="../Controlador/UsuarioControlador.php">
        <input type="hidden" name="accion" value="registrar">
        <input type="hidden" name="rol" value="proveedor">

        <label>Nombre completo:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Correo electronico:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña</label><br>
        <input type="password" name="password" minlength="6" required><br><br>


        <button type="submit">Registrase</button>
</form>

<?php if (isset($_GET['error'])): ?>
    <p style="color:red;">
        <?php 
        if ($_GET['error'] === 'correo_existente') {
            echo " Este correo ya está registrado, usa otro.";
        }
        ?>
    </p>
<?php endif; ?>


<p><a href="index.php">Volver</a></p>
</body>
</html>