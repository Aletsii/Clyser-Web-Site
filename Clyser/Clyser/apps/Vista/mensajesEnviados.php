<?php 
session_start();
require_once "../Modelo/Mensaje.php";

if(!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}
$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];
$mensajeModel = new Mensaje();
$mensajes = $mensajeModel->listarEnviados($idUsuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mensajes enviados</title>
   <link rel="icon" type="image/png" href="../../public/img/C.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
   <div class="mensajeria-container fade-in-up">
  <div class="mensajeria-header">
      <h2><i class="fa-solid fa-paper-plane icono"></i> Bandeja de Enviados</h2>
      <div class="opciones">
        <a href="mensajeria.php"><i class="fa-solid fa-inbox icono"></i> Ver recibidos</a> |
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


  <?php if (empty($mensajes)): ?>
 <p class="sin-calificaciones">No has enviado mensajes</p>
 <?php else: ?>
  <?php foreach ($mensajes as $m): ?>
   <div class="mensaje-card">
          <div class="mensaje-header">
            <h3><i class="fa-regular fa-envelope icono"></i> <?php echo htmlspecialchars($m["asunto"]); ?></h3>
            <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($m["fecha"]); ?></span>
          </div>

          <div class="mensaje-info">
            <p><strong><i class="fa-solid fa-user-check icono"></i> Destinatario:</strong> <?php echo htmlspecialchars($m["nombreDestinatario"]); ?></p>
          </div>

          <div class="mensaje-content">
            <p> <em>Mensaje enviado correctamente.</em></p>
          </div>

          <a href="verMensaje.php?id=<?php echo $m["idMensaje"]; ?>" class="btn-ver" style="margin-top:10px;">
            <i class="fa-solid fa-eye"></i> Ver mensaje
          </a>
        </div>
  <?php endforeach; ?>
 </div>

 <?php endif; ?>
 
 </body>
 </html>