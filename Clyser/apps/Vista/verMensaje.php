<?php
session_start();
require_once "../Modelo/Mensaje.php";
require_once "../Modelo/Mensaje.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}


$idMensaje = (int)($_GET["id"] ?? 0);
$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];


$mensajeModel = new Mensaje();
$mensaje = $mensajeModel->obtenerMensaje($idMensaje, $idUsuario, $rolUsuario);

if ($mensaje) {
  $mensajeModel->marcarComoLeido($idMensaje);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver mensaje</title>
  <link rel="stylesheet" href="../../public/css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="icon" type="image/png" href="../../public/img/C.png">
</head>
<body>
  
<body>
  <div class="mensajeria-container fade-in-up">
    <div class="mensajeria-header">
      <h2><i class="fa-solid fa-envelope-open-text icono"></i> Detalles del Mensaje</h2>
       <div class="opciones">
        <a href="mensajeria.php"><i class="fa-solid fa-inbox icono"></i> Volver a bandeja</a> |
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

  <?php if (!$mensaje): ?>
    <p class="error" style="text-align:center;"> Error: mensaje no encontrado.</p>
    <?php else: ?>
    <?php 
       $foto = !empty($mensaje["fotoPerfilRemitente"])
          ? "../../public/img/uploads/" . htmlspecialchars($mensaje["fotoPerfilRemitente"])
          : "../../public/img/avatarPredeterminada.png";
      ?>

      <div class="ver-mensaje-card">
       <div class="mensaje-usuario">
        <img src="<?php echo $foto; ?>" alt="Remitente" class="avatar-mensaje">
          <div>
             <p><strong><?php echo htmlspecialchars($mensaje["nombreRemitente"]); ?></strong></p>
            <p class="fecha"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($mensaje["fecha"]); ?></p>
          </div>
          </div>

<div class="mensaje-datos">
        <p><i class="fa-solid fa-user-check icono"></i> <strong>Para:</strong> <?php echo htmlspecialchars($mensaje["nombreDestinatario"]); ?></p>
        <p><i class="fa-solid fa-tag icono"></i> <strong>Asunto:</strong> <?php echo htmlspecialchars($mensaje["asunto"]); ?></p>
</div>

        <div class="contenido-mensaje">
          <?php echo nl2br(htmlspecialchars($mensaje["contenido"])); ?>
        </div>

  <div class="acciones">
          <?php if ($mensaje["idRemitente"] != $_SESSION["idUsuario"]): ?>
            <a href="enviarMensaje.php?idDestinatario=<?php echo $mensaje["idRemitente"]; ?>" class="btn-responder">
              <i class="fa-solid fa-reply"></i> Responder
            </a>
          <?php endif; ?>

          
        </div>
      </div>

    <?php endif; ?>
  </div>
</body>
</html>
