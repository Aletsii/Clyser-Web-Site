<?php
session_start();
if (!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "admin") {
  header("Location: index.php?error=no_autorizado");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de administración | Clyser</title>
   <link rel="icon" type="image/png" href="../../public/img/C.png">
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
    <li><a href="mensajeria.php">Mensajería</a></li>
    <li><a href="../../apps/Controlador/UsuarioControlador.php?accion=logout">Salir</a></li>
  </ul>
</nav>
 <h2>Bienvenido administrador</h2>
 <p style="text-align:center;">Gestiona usuarios, servicios y comunicaciones desde un solo lugar.</p>

<section style="display:flex;justify-content:center;flex-wrap:wrap;gap:20px;margin-top:30px;">
  <div class="tarjeta">
    <h3> Gestionar usuarios</h3>
    <p>Ver, editar o eliminar usuarios registrados.</p>
    <a href="gestionUsuarios.php" class="btn-ver">Ver usuarios</a>
  </div>

  <div class="tarjeta">
    <h3> Gestionar servicios</h3>
    <p>Supervisar y moderar los servicios publicados.</p>
    <a href="gestionServicios.php" class="btn-ver">Ver servicios</a>
  </div>
</section>
</body>
</html>