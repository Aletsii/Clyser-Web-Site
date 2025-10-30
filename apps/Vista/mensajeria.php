<?php
session_start();
require_once "../Modelo/Mensaje.php";
require_once "../Modelo/Usuario.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}


$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];
$mensajeModel = new Mensaje();

$mensajes = $mensajeModel->listarBandeja($idUsuario, $rolUsuario);

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis mensajes</title>
 <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="icon" type="image/png" href="../../public/img/C.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
   <div class="mensajeria-container fade-in-up">

   
   <div class="mensajeria-header">
      <h2><i class="fa-solid fa-inbox icono"></i> Bandeja de Entrada</h2>
      <div class="opciones">
        <a href="mensajesEnviados.php"><i class="fa-solid fa-paper-plane icono"></i> Ver enviados</a> |
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

<?php if (isset($_GET["msg"]) && $_GET["msg"] === "mensaje_enviado"): ?>
  <p class="success" style="text-align:center;">✅ Mensaje enviado correctamente.</p>
<?php endif; ?>


  <?php if (empty($mensajes)): ?>
    <p class="sin-calificaciones">No tienes mensajes nuevos.</p>
  <?php else: ?>
  <?php foreach ($mensajes as $m): ?>
  <?php  $foto = !empty($m["fotoPerfilRemitente"]) 
        ? "../../public/img/uploads/" . htmlspecialchars($m["fotoPerfilRemitente"]) 
        : "../../public/img/avatarPredeterminada.png";
    ?>
    <div class="mensaje-card">
      <div class="mensaje-header">
       <h3><i class="fa-solid fa-envelope icono"></i> <?php echo htmlspecialchars($m["asunto"]); ?></h3>
       <span><i class="fa-regular fa-clock"></i><?php echo htmlspecialchars($m["fecha"]); ?></span>
       </div>

       <div class="mensaje-info">
        <div class="mensaje-usuario">
          <img src="<?php echo $foto; ?>" alt="Remitente" class="avatar-mensaje">
        <p><strong><i class="fa-solid fa-user icono"></i>Remitente:</strong><?php echo htmlspecialchars($m["nombreRemitente"]) ?></p>
       </div>
       </div>

       <div class="mensaje-content">
        <p><em>Haz clic en "Ver mensaje" para verlo completo</em></p>
       </div>

   
          <a href="verMensaje.php?id=<?php echo $m["idMensaje"]; ?>" class="btn-ver" style="margin-top:10px;">
            <i class="fa-solid fa-eye"></i> Ver mensaje
          </a>
        </div>    
  <?php endforeach; ?>
 <?php endif; ?>
   </div>
  
</body>
</html>
