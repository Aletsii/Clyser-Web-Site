<?php
session_start();
if (!isset($_SESSION["idUsuario"])){
    header("Location: index.php?error=mo_logueado");
    exit;
}
require_once "../Modelo/Reserva.php";
require_once "../Modelo/Calificacion.php";
require_once "../Modelo/Mensaje.php";


$idCliente = (int)$_SESSION["idUsuario"];
$rol = $_SESSION["rol"] ?? "cliente";

$mensajeModel = new Mensaje();
$idUsuario = $_SESSION["idUsuario"];
$rolUsuario = $_SESSION["rol"];
$nuevos = $mensajeModel->contarNoLeidos($idUsuario, $rolUsuario);



$reservaModel = new Reserva();
$calificarModel = new Calificacion();
$reservas = $reservaModel->mostrarReservaCliente($idCliente);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis reservas</title>
<link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/png" href="../../public/img/C.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
<body>


<h2>Mis reservas</h2>
<div class="mensajeria-header">
    <div class="opciones">
        <a href="servicios.php"><i class="fa-solid fa-calendar-check"></i> Ver servicios</a> |
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

      <!--primero ponemos un aviso para verificar si ya fue calificada -->
       <?php if (isset($_GET["msg"])): ?> 
        <?php if ($_GET["msg"] === "ya_calificada"): ?>
          <p class="error" style="text-aling:center;">Ya calificaste esta reserva</p>
          <?php elseif ($_GET["msg"] === "calificacion_ok"): ?>
           <p class="success" style="text-align:center;">✅ Calificación registrada correctamente.</p>
           <?php endif; ?>
           <?php endif; ?>



  <?php if (empty($reservas)): ?>
    <p>No tienes reservas</p>

    <?php else: ?>
        <?php foreach ($reservas as $r): ?>

           <?php 
      $avatar = !empty($r["fotoPerfil"])
      ? "../../public/img/uploads/" . htmlspecialchars($r["fotoPerfil"])
      : "../../public/img/avatarPredeterminada.png";
       ?>
           
          <div class="tarjeta-reserva">
    <h3><?php echo htmlspecialchars($r["titulo"]); ?></h3>

     
       <div class="reserva-detalles">
        <div class="usuario-info">
     <img src="<?php echo $avatar; ?>" alt="proveedor" class="avatar-usuario">
         <p><strong><?php echo htmlspecialchars($r["proveedor"]); ?></strong></p>
      </div>

    <p><i class="fa-solid fa-briefcase"></i> <strong>Categoría:</strong> <?php echo htmlspecialchars($r["categoria"]); ?></p>
    <p><i class="fa-regular fa-calendar-days"></i> <strong>Fecha:</strong> <?php echo htmlspecialchars($r["fecha"]); ?></p>
    <p><i class="fa-solid fa-location-dot"></i> <strong>Dirección:</strong> <?php echo htmlspecialchars($r["calle"]) . " " . (int)$r["numero"]; ?></p>
      
        </div>

        <?php
        $estado = htmlspecialchars($r["estadoReserva"]);
        $claseEstado = strtolower($estado);
        ?>


      <div class="reserva-footer">
        <span class="estado <?php echo $claseEstado; ?>"><?php echo ucfirst($estado); ?></span>
        <div>
        <?php if ($r["estadoReserva"] === "finalizada"): ?>
        <?php if ($calificarModel->yaCalificada($r['idReserva'])): ?>
          <span class="estado finalizada">Calificada</span>
        <?php else: ?>
          <a href="calificar.php?idReserva=<?php echo $r['idReserva']; ?>" class="btn-accion btn-calificar">
            <i class="fa-solid fa-star"></i> Calificar
          </a>
        <?php endif; ?>
       
      <?php endif; ?>
    </div>
  </div>
</div>
 <?php endforeach; ?>
 <?php endif; ?>
  </body>
  </html>