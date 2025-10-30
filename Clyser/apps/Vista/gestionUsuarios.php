<?php   
require_once "../../apps/Modelo/Usuario.php";
session_start();

if(!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../../public/index.php?error=no_autorizado");
    exit;
}

$usuarioModel = new Usuario();

$conn = (new Conexion())->getConexion();
$result = $conn->query("SELECT idUsuario, nombre, correoElectronico, rol, fechaRegistro FROM persona WHERE rol <> 'admin'");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de usuarios | Clyser</title>
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
        <li><a href="../Controlador/UsuarioControlador.php?accion=logout"></a></li>
    </ul>
</nav>

<div class="panel-admin">
<h3> Gestión de usuarios</h3>
<p style="text-align:center;">Visualiza, elimina o cambia el rol de los usuarios del sistema.</p>
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


    <tbody>
        <div class="tarjeta-lista">
        <?php foreach ($usuarios as $u): ?>
            <div class="tarjeta-item">
            
           <h4><?= htmlspecialchars($u["nombre"]) ?></h4>
      <p><strong>ID:</strong> <?= $u["idUsuario"] ?></p>
      <p><strong>Correo:</strong> <?= htmlspecialchars($u["correoElectronico"]) ?></p>
      <p><strong>Rol:</strong> <?= ucfirst($u["rol"]) ?></p>
      <p><strong>Fecha de Registro:</strong> <?= $u["fechaRegistro"] ?></p>
      <a href="../Controlador/adminControlador.php?accion=eliminarUsuario&id=<?= $u["idUsuario"] ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
    </div>
      <?php endforeach ?>          
    </tbody>
</div>

</body>