<?php 
session_start();
require_once "../Modelo/Mensaje.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}

$mensajeModel = new Mensaje();
$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];
$nuevos = $mensajeModel->contarNoLeidos($idUsuario, $rolUsuario);


$home = "index.php";
if (isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] === "cliente")  $home = "principalCliente.php";
    if ($_SESSION["rol"] === "proveedor")$home = "principalProveedor.php";
    if ($_SESSION["rol"] === "admin")    $home = "principalAdmin.php";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Cliente | Clyser</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="icon" type="image/png" href="../../public/img/C.png">
    
</head>
<body>

<?php
  
  $home = "principalCliente.php";
  ?>
<nav class="navbar">
  <ul>
    <li class="brand">
      <a href="<?php echo $home; ?>" class="brand-link">
        <img src="../../public/img/Clyser.png" alt="Clyser" class="brand-logo">
        <span class="brand-name"></span>
      </a>
    </li>
    <li><a href="servicios.php">Servicios</a></li>
    <li><a href="reservasCliente.php">Mis reservas</a></li>
    <li><a href="mensajeria.php">Mensajería <?php if ($nuevos > 0) echo "<span class='badge'>$nuevos</span>"; ?></a></li>
    <li><a href="enviarMensaje.php?idDestinatario=1"> Contactar administrador</a></li>
    <li><a href="perfilCliente.php">Perfil</a></li>
    <li><a href="../Controlador/UsuarioControlador.php?accion=logout">Salir</a></li>
  </ul>
</nav>

<header class="fade-in-up" style="text-align:center; margin: 40px auto;">
  <h2 >
    Bienvenido <?php echo htmlspecialchars($_SESSION["nombre"]); ?> <br>
    <small >Conectamos clientes y proveedores en un solo lugar.</small>
  </h2>
</header>



<section style="display:flex;flex-wrap:wrap;justify-content:center;gap:20px;margin:30px;">
  <div class="tarjeta fade-in-up" style="width:260px;text-align:center;">
    <h3> Ver servicios</h3>
    <p>Explora y contrata a los mejores proveedores.</p>
    <a href="servicios.php" class="btn-ver">Ir a servicios</a>
  </div>

  <div class="tarjeta fade-in-up" style="width:260px;text-align:center;">
    <h3> Mis reservas</h3>
    <p>Consulta y califica tus reservas activas y finalizadas.</p>
    <a href="reservasCliente.php" class="btn-ver">Ver reservas</a>
  </div>

  <div class="tarjeta fade-in-up" style="width:260px;text-align:center;">
    <h3> Mensajería</h3>
    <p>Comunícate directamente con los proveedores.</p>
    <a href="mensajeria.php" class="btn-ver">Ir al chat</a>
  </div>

  <div class="tarjeta fade-in-up" style="width:260px;text-align:center;">
    <h3> Mi perfil</h3>
    <p>Actualiza tu información y foto de perfil.</p>
    <a href="perfilCliente.php" class="btn-ver">Ver perfil</a>
  </div>
</section>


</body>
</html>
