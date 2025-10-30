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

<form action="../Controlador/adminControlador.php?accion=editarServicio&id=<?= $id ?>" method="POST">
<label for="titulo">Titulo</label>
<input type="text" name="titulo" value="<? htmlspecialchars($servicio['titulo']) ?>" required> 

<label for="descripcion">Descripcion</label>
<textarea name="descripcion" rows="4" required><?= htmlspecialchars($servicio['descripcion']) ?></textarea>

<label for="precio">Precio (UYU)</label>
<input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($servicio['precio']) ?>">

<label for="categoria">Categoria</label>
<input type="text" name="categoria" value="<?= htmlspecialchars($servicio['categoria']) ?>">

<button type="submit" class="btn">Guardar cambios</button>

</form>
</body>
</html>
