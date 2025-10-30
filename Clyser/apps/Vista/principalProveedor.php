<?php
session_start();
if (!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "proveedor") {
  header("Location: index.php?error=no_autorizado");
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
  <title>Inicio - Proveedor | Clyser</title>
  <link rel="stylesheet" href="../../public/css/style.css">
   <link rel="icon" type="image/png" href="../../public/img/C.png">
</head>
<body>

  <?php
  
  $home = "principalProveedor.php";
  ?>
  <nav class="navbar">
    <ul>
      <li class="brand">
        <a href="<?php echo $home; ?>" class="brand-link">
          <img src="../../public/img/Clyser.png" alt="Clyser" class="brand-logo">
          <span class="brand-name"></span>
        </a>
      </li>
      <li><a href="publicarServicio.php">Publicar servicio</a></li>
      
      <li><a href="reservasRecibidas.php">Reservas</a></li>
      <li><a href="mensajeria.php">Mensajería <?php if ($nuevos > 0) echo "<span class='badge'>$nuevos</span>"; ?></a></li>
       <li><a href="enviarMensaje.php?idDestinatario=1"> Contactar administrador</a></li>
      <li><a href="perfilProveedor.php">Perfil</a></li>
      <li><a href="../Controlador/UsuarioControlador.php?accion=logout">Salir</a></li>
    </ul>
  </nav>

  <main style="text-align:center; margin-top:50px;">
    <h2>Bienvenido proveedor </h2>
    <p style="font-size:18px;">Administra tus servicios, reservas y comunícate con tus clientes.</p>

    <div style="display:flex; justify-content:center; flex-wrap:wrap; gap:20px; margin-top:40px;">
      
      <div class="tarjeta fade-in-up" style="max-width:250px;">
        <h3> Publicar servicio</h3>
        <p>Ofrece tus servicios a nuevos clientes.</p>
        <a href="publicarServicio.php"><button>Publicar</button></a>
      </div>

      <div class="tarjeta fade-in-up" style="max-width:250px;">
        <h3> Mi perfil</h3>
        <p>Gestiona tus servicios y actualiza tu perfil.</p>
        <a href="perfilProveedor.php"><button>Ver perfil</button></a>
      </div>

      <div class="tarjeta fade-in-up" style="max-width:250px;">
        <h3> Reservas recibidas</h3>
        <p>Gestiona las reservas de tus clientes.</p>
        <a href="reservasRecibidas.php"><button>Ver reservas</button></a>
      </div>

      <div class="tarjeta fade-in-up" style="max-width:250px;">
        <h3> Mensajería</h3>
        <p>Comunícate directamente con tus clientes.</p>
        <a href="mensajeria.php"><button>Ir al chat</button></a>
      </div>

    </div>
  </main>

</body>
</html>
