<?php
require_once "../Modelo/Calificacion.php";
require_once "../Modelo/Usuario.php";
require_once "../Modelo/Mensaje.php";

session_start();
if (!isset($_SESSION["idUsuario"])) {
  header("Location: index.php?error=no_logueado");
  exit;
}

$idCliente = $_SESSION["idUsuario"];
$usuarioModel = new Usuario();
$calificarModel = new Calificacion();
$mensajeModel = new Mensaje();

$usuario = $usuarioModel->obtenerUsuarioPorId($idCliente);
$calificaciones = $calificarModel->listarPorCliente($idCliente);

$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];
$nuevos = $mensajeModel->contarNoLeidos($idUsuario, $rolUsuario);

// Foto por defecto
$fotoPerfil = "../../public/img/avatarPredeterminada.png";
if (!empty($usuario["fotoPerfil"])) {
  $fotoPerfil = "../../public/img/uploads/" . htmlspecialchars($usuario["fotoPerfil"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["foto"])) {
  $archivo = $_FILES["foto"];
  $nombreTmp = $archivo["tmp_name"];
  $nombreFinal = "foto_" . $idCliente . "_" . time() . ".jpg";
  $rutaDestino = "../../public/img/uploads/" . $nombreFinal;

  $permitidos = ["image/jpeg", "image/png", "image/jpg"];
  if (in_array($archivo["type"], $permitidos)) {
    if (move_uploaded_file($nombreTmp, $rutaDestino)) {
      $usuarioModel->actualizarFoto($idCliente, $nombreFinal);
      header("Location: perfilCliente.php?msg=foto_actualizada");
      exit;
    } else {
      $error = "Error al guardar el archivo.";
    }
  } else {
    $error = "Formato no permitido.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil del Cliente</title>
  <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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


<main class="perfil-container fade-in-up">
  <div class="perfil-card">
    <div class="perfil-foto">
      <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil">
    </div>

    <h2><?php echo htmlspecialchars($usuario["nombre"]); ?></h2>
    <p class="correo"><?php echo htmlspecialchars($usuario["correo"]); ?></p>
    <p class="rol"><?php echo ucfirst($usuario["rol"]); ?></p>

    
    <form method="POST" enctype="multipart/form-data" class="form-foto">
      <label for="foto" class="custom-file-upload">
         Seleccionar nueva foto
      </label>
      <input id="foto" type="file" name="foto" accept="image/*" required>
      <button type="submit" class="btn-subir">⬆ Subir</button>
    </form>

    <?php if (isset($error)): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php elseif (isset($_GET["msg"]) && $_GET["msg"] === "foto_actualizada"): ?>
      <p class="success"> Foto actualizada correctamente.</p>
    <?php endif; ?>

    <h3> Calificaciones realizadas</h3>

  <?php if (empty($calificaciones)): ?>
    <p class="sin-calificaciones">Todavía no realizaste calificaciones.</p>
  <?php else: ?>
    <div class="tarjeta-lista">
      <?php foreach ($calificaciones as $c): ?>
        <div class="tarjeta-item">
          <h4><?php echo htmlspecialchars($c["servicio"]); ?></h4>
          <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($c["proveedor"]); ?></p>
          <p><strong>Puntuación:</strong> <?php echo $c["puntuacion"]; ?>/5 ⭐</p>
          <p><strong>Comentario:</strong> <?php echo htmlspecialchars($c["comentario"]); ?></p>
          <p class="fecha">Fecha: <?php echo $c["fechaCalificacion"]; ?></p>
        </div>

      <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

</body>
</html>
