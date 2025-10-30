<?php
require_once "../../apps/Modelo/Servicio.php";
session_start();

if (!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "admin") {
  header("Location: ../../public/index.php?error=no_autorizado");
  exit;
}

$servicioModel = new Servicio();
$conn = (new Conexion())->getConexion();
$res = $conn->query("SELECT s.idServicio, s.titulo, s.descripcion, s.precio, s.categoria, p.nombre AS proveedor
FROM servicio s
JOIN persona p ON p.idUsuario = s.idProveedor
ORDER BY s.fechaServicio DESC");
$servicios = $res->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de servicios | Clyser</title>
  <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="icon" type="image/png" href="../../public/img/C.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <li><a href="../Controlador/UsuarioControlador.php?accion=logout">Salir</a></li>
  </ul> 
   </nav>

   <div class="panel-admin">
   <h2> Gestión de servicios</h2>
<p style="text-align:center;">Supervisa o elimina servicios publicados por los proveedores.</p>

<div class="mensajeria-header">
  <div class="opciones">
      
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

     <div class="tarjeta-lista">
    <?php foreach ($servicios as $s): ?>
      <div class="tarjeta-item">
              <h3><i class="fa-solid fa-briefcase"></i> <?= htmlspecialchars($s["titulo"]) ?></h3>
        <p><strong>ID Servicio:</strong> <?= $s["idServicio"] ?></p>
        <p><strong>Proveedor:</strong> <?= htmlspecialchars($s["proveedor"]) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($s["descripcion"]) ?></p>
        <p><strong>Precio por hora:</strong> $<?= number_format($s["precio"], 2) ?></p>

        <a href="../Controlador/adminControlador.php?accion=eliminarServicio&id=<?= $s["idServicio"] ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este servicio?')">Eliminar</a>
      </div>
    <?php endforeach ?>
    </div>
    </div>
  
  

</body>
</html>