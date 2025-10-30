<?php 
session_start();
if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}

require_once "../Modelo/Mensaje.php";
$mensajeModel = new Mensaje();
$nuevos = $mensajeModel->contarNoLeidos($_SESSION["idUsuario"], $_SESSION["rol"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar servicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="icon" type="image/png" href="../../public/img/C.png">

    
</head>
<body>

<h1>Publica un servicio</h1>
<div class="mensajeria-header">
    <div class="opciones">
        <a href="servicios.php"><i class="fa-solid fa-calendar-check"></i> Ver servicios</a> |
  <?php 
       if ($_SESSION["rol"] === "proveedor") {
        echo '<a href="principalProveedor.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "cliente") {
      echo '<a href="principalCliente.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "admin") {
          echo '<a href="principalAdmin.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    }
    ?>
</div>
</div>


<form method="POST" action="../Controlador/servicioControlador.php?accion=guardar">
    <label>Titulo:</label><br>
     
    <input type="text" name="titulo" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea><br>

    <label>Precio:</label>
    <input type="number" step="0.01" name="precio" required><br>

    <label>Categoría:</label>
    <select name="categoria">
        <option value="informatica">Informatica</option>
        <option value="carpinteria">Carpinteria</option>
        <option value="limpieza">Limpieza</option>
        <option value="mecanica">Mecanica</option>
        <option value="construccion">Construccion</option>
        <option value="pintureria">Pintureria</option>
        <option value="plomeria">Plomeria</option>
        <option value="otros">Otros</option>
        
    </select>

    <button type="submit">Publicar</button>
</form>
    
</body>
</html>