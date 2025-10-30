<?php
session_start();
require_once "../Modelo/Calificacion.php";
require_once "../Modelo/Servicio.php";
require_once "../Modelo/Usuario.php";

if (!isset($_SESSION["idUsuario"])) {
  header("Location: index.php?error=no_logueado");
  exit;
}

$usuarioModel = new Usuario();
$idProveedor = $_SESSION["idUsuario"];
$calificacionModel = new Calificacion();
$servicioModel = new Servicio();


//calificaciones
  $promedio = $calificacionModel->obtenerPromedio($idProveedor);
  $calificaciones = $calificacionModel->listarPorProveedor($idProveedor);

  //obtener servicios
  $usuario = $usuarioModel->obtenerUsuarioPorId($idProveedor);
  $servicios = $servicioModel->listarPorProveedor($idProveedor);

$fotoPerfil = "../../public/img/avatarPredeterminada.png";
if (!empty($usuario["fotoPerfil"])) {
  $fotoPerfil = "../../public/img/uploads/" . htmlspecialchars($usuario["fotoPerfil"]);
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["foto"])){
    $archivo = $_FILES["foto"];
    $nombreTmp = $archivo["tmp_name"];
    $nombreFinal = "foto_" . $idProveedor . "_" . time() . ".jpg";
    $rutaDestino = "../../public/img/uploads/" . $nombreFinal;
  

  //validacion para tipo de archivo
  $permitidos = ["image/jpeg", "image/png", "image/jpg"];
    if (in_array($archivo["type"], $permitidos)) {
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            // Guardar en BD
            $usuarioModel->actualizarFoto($idProveedor, $nombreFinal);
            header("Location: perfilProveedor.php?msg=foto_actualizada");
            exit;
        } else {
            $error = "Error al guardar el archivo.";
        }
    } else {
        $error = "Formato no permitido.";
    }
  }
require_once "../Modelo/Mensaje.php";
$mensajeModel = new Mensaje();
$nuevos = $mensajeModel->contarNoLeidos($_SESSION["idUsuario"], $_SESSION["rol"]);

  ?>

  <!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Perfil del Proveedor</title>
 <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
   <link rel="icon" type="image/png" href="../../public/img/C.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<?php
$home = "principalProveedor.php";
  ?>
  <nav class="navbar">
    <ul>
      <li class="brand">
        <a href="<?php echo $home; ?>" class="brand-link">
          <img src="../../public/img/clyser.png" alt="Clyser" class="brand-logo">
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


  <?php if (isset($_GET["msg"])): ?> 
        <?php if ($_GET["msg"] === "servicio_editado"): ?>
          <p class="success" style="text-aling:center;">Servicio editado correctamente</p>
          
           <?php endif; ?>
           <?php endif; ?>


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
            <p class="success" style="color:green;"> Foto actualizada correctamente.</p>
        <?php endif; ?>
    
        <div class="perfil-promedio">
            <p><strong>⭐ Promedio general:</strong> 
        <?php echo $promedio ? $promedio . " / 5" : "Sin calificaciones aún"; ?>
      </p>
    </div>
        </div>

      <hr class="separador">

      <h3 class="subtitulo">Mis servicios publicados </h3>
  


  <?php if (empty($servicios)): ?>
   <p class="mensaje-vacio">No tienes servicios publicados.</p>
   <?php else: ?>
    <div class="tarjeta-lista">
      <?php foreach ($servicios as $s): ?>
       <div class="tarjeta-item">
       <h4><?php echo htmlspecialchars($s["titulo"]); ?></h4>
        <p><i class="fa-solid fa-align-left"></i> <?php echo htmlspecialchars($s["descripcion"]); ?></p>
          <p><i class="fa-solid fa-dollar-sign"></i> <strong>Precio por hora:</strong> <?php echo htmlspecialchars($s["precio"]); ?> UYU</p>
          <p><i class="fa-solid fa-layer-group"></i> <strong>Categoría:</strong> <?php echo htmlspecialchars($s["categoria"]); ?></p>
          <a href="editarServicioProveedor.php?id=<?= $s["idServicio"] ?>" class="btn-ver">  Editar </a>

          <a href="../Controlador/servicioControlador.php?accion=eliminar&id=<?php echo $s['idServicio']; ?>"
             class="btn-eliminar"
             onclick="return confirm('¿Seguro que quieres eliminar este servicio?');">
             <i class="fa-solid fa-trash"></i> Eliminar
          </a>
         </div>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <hr class="separador">

    <h3 class="subtitulo"> Opiniones de clientes</h3>
    <?php if (empty($calificaciones)): ?>
      <p class="mensaje-vacio">Aún no tienes calificaciones.</p>
      <?php else: ?>
        <div class="tarjeta-lista">
        <?php foreach ($calificaciones as $c): ?>
          <div class="tarjeta-item">
          <p><strong>Cliente:</strong>
           <a href="verPerfil.php?id=<?php echo $c['idCliente']; ?>">
              <?php echo htmlspecialchars($c["nombre"]); ?>
            </a>
          </p>
          <p class="puntuacion"><?php echo str_repeat("⭐", (int)$c["puntuacion"]); ?></p>
         <p><strong>Comentario:</strong> <?php echo htmlspecialchars($c["comentario"]); ?></p>
          <small><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($c["fechaCalificacion"]); ?></small>
                    </div>
  <?php endforeach; ?>
</div>
          <?php endif; ?>

</div>
 </body>
 </html>