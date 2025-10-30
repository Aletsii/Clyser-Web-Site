<?php 
require_once "../Modelo/Usuario.php";
require_once "../Modelo/Mensaje.php";
session_start();

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php? error=no_logueado");
    exit;
}

$idRemitente = $_SESSION["idUsuario"];
$idDestinatario = isset($_GET["idDestinatario"]) ? (int)$_GET["idDestinatario"] : 0;

$usuarioModel = new Usuario();
$destinatario = $usuarioModel->obtenerUsuarioPorId($idDestinatario);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Enviar mensaje</title>
 <link rel="icon" type="image/png" href="../../public/img/C.png">
 <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
 <div class="mensajeria-container fade-in-up">

 <div class="mensajeria-header">
 <h2><i class="fa-solid fa-paper-plane icono"></i> Redactar mensaje</h2>
 <div class="opciones">
  <a href="mensajeria.php"><i class="fa-solid fa-inbox icono"></i> Ir a bandeja</a> |
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


<div class="mensaje-form">
<form method="POST" action="../Controlador/mensajeControlador.php">
  <input type="hidden" name="accion" value="enviar">
  <input type="hidden" name="idDestinatario" value="<?php echo $idDestinatario; ?>">

<h3>
 <i class="fa-solid fa-user-pen icono"></i> Enviar mensaje a: 
 <span style="color:#007bff;"><?php echo htmlspecialchars($destinatario["nombre"]); ?></span>
 </h3>


 <label for="asunto"><i class="fa-solid fa-tag icono"></i> Asunto:</label>
<input type="text" id="asunto" name="asunto" placeholder="Escribí el asunto del mensaje..." required>

<label for="contenido"><i class="fa-solid fa-comment-dots icono"></i> Contenido:</label>
<textarea id="contenido" name="contenido" rows="6" placeholder="Escribí tu mensaje aquí..." required></textarea>


        <button type="submit"><i class="fa-solid fa-paper-plane"></i> Enviar mensaje</button>
</form>
</div>
</div>

</body>
</html>