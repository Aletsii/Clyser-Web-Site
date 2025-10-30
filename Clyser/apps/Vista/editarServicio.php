<?php 
require_once "../Modelo/Servicio.php";
session_start();

if (!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "admin"){
    header("Location: ../../public/index.php?error=no_autorizado");
    exit;
}

$id = (int)($_GET["id"] ?? 0);
$conn = (new Conexion())->getConexion();
$stmt = $conn->prepare("SELECT * FROM servicio WHERE idServicio = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$servicio = $res->fetch_assoc();
$stmt->close();


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar servicio | Clyser</title>
  <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<nav class="navbar">
    <ul>
    <li class="brand">
      <a href="principalAdmin.php" class="brand-link">
        <img src="../../public/img/Clyser.png" alt="Clyser" class="brand-logo">
        <span class="brand-name"></span>
      </a>
    </li>
    <li><a href="gestionUsuarios.php">Usuarios</a></li>
    <li><a href="gestionServicios.php">Servicios</a></li>
    <li><a href="../../apps/Controlador/UsuarioControlador.php?accion=logout">Salir</a></li>
  </ul>
</nav>

<h2> Editar servicio</h2>

<form action="../Controlador/adminControlador.php?accion=editarServicio&id=<?= $s["idServicio"] ?>" method="POST">
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
<button type="submit" class="btn-ver">Guardar cambios</button>

</form>
</body>
</html>
