<?php
session_start();
if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}
require_once "../Modelo/Reserva.php";

$idProveedor = (int)$_SESSION["idUsuario"];
$rol         = $_SESSION["rol"] ?? "proveedor";

$reservaModel = new Reserva();
$reservas = $reservaModel->mostrarReservaProveedor($idProveedor);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reservas recibidas</title>
 <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
   <link rel="icon" type="image/png" href="../../public/img/C.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
<body>
  <h2>Reservas recibidas</h2>
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

  <?php if (isset($_GET["msg"]) && $_GET["msg"] === "estado_actualizado"): ?>
     <p style="color: green; font-weight: bold; text-align:center;">Estado actualizado correctamente.</p>
<?php elseif (isset($_GET["error"])): ?>
  <p style="color: red; font-weight: bold; text-align:center;">
    Error: <?php echo htmlspecialchars($_GET["error"]); ?>
  </p>
<?php endif; ?>

  <?php if (empty($reservas)): ?>
    <p>No recibiste reservas aún.</p>
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
            <img src="<?php echo $avatar; ?>" alt="Cliente" class="avatar-usuario">
         <p><strong><?php echo htmlspecialchars($r["cliente"]); ?></strong></p>
      </div>
         
         <p><i class="fa-solid fa-briefcase"></i> <strong>Categoría:</strong> <?php echo htmlspecialchars($r["categoria"]); ?></p>
         <p><i class="fa-regular fa-calendar-days"></i> <strong>Fecha:</strong> <?php echo htmlspecialchars($r["fecha"]); ?></p>
         <p><i class="fa-solid fa-location-dot"></i> <strong>Dirección:</strong> <?php echo htmlspecialchars($r["calle"]) . " " . (int)$r["numero"]; ?></p>
         <p><i class="fa-solid fa-briefcase"></i> <strong>Detalle:</strong> <?php echo htmlspecialchars($r["detalle"]); ?></p>
         </div>

  <div class="reserva-footer">
    <span class="estado <?php echo strtolower($r["estadoReserva"]); ?>">
      <?php echo ucfirst($r["estadoReserva"]); ?>
    </span>

      <div class="acciones-reserva">
      <?php if ($r["estadoReserva"] === "pendiente"): ?>
        <form action="../Controlador/reservaControlador.php" method="POST" style="display:inline;">
          <input type="hidden" name="accion" value="cambiarEstado">
          <input type="hidden" name="id" value="<?php echo $r['idReserva']; ?>">
          <input type="hidden" name="estado" value="aceptada">
          <button type="submit" class="btn-accion btn-aceptar"><i class="fa-solid fa-check"></i> Aceptar</button>
        </form>

        <form action="../Controlador/reservaControlador.php" method="POST" style="display:inline;">
          <input type="hidden" name="accion" value="cambiarEstado">
          <input type="hidden" name="id" value="<?php echo $r['idReserva']; ?>">
          <input type="hidden" name="estado" value="rechazada">
          <button type="submit" class="btn-accion btn-rechazar"><i class="fa-solid fa-xmark"></i> Rechazar</button>
        </form>
        
      <?php endif; ?>

    <?php if ($r["estadoReserva"] !== "finalizada"): ?>
        <a href="../Controlador/reservaControlador.php?accion=finalizar&id=<?php echo $r['idReserva']; ?>"
          class="btn-accion btn-finalizar"
          onclick="return confirm('¿Confirmas que esta reserva fue completada?');">
          <i class="fa-solid fa-flag-checkered"></i> Finalizar
        </a>
        
      <?php endif; ?>
      </div>
  </div>
</div>

      <?php endforeach; ?>
      <?php endif; ?>

      
    

  <script>
  setTimeout(() => {
    const msg = document.querySelector("p[style]");
    if (msg) msg.style.display = "none";
  }, 4000); // 4 segundos
</script>


</body>
</html>
