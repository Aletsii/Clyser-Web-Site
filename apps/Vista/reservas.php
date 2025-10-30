<?php
session_start();
require_once "../Modelo/Servicio.php";

if (!isset($_GET["id"])) {
    header("Location: servicios.html");
    exit;
}

$idServicio = (int)$_GET["id"];
$servicioModel = new Servicio();
$serv = $servicioModel->obtenerServicioPorId($idServicio);

if (!$serv) {
    echo "<p>Servicio no encontrado.</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clyser - Reservar servicio</title>
     <link rel="stylesheet" href="../../public/css/style.css">
     <link rel="icon" type="image/png" href="../../public/img/C.png">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
</head>
<body>
    <h2>Reservar: 
        <?php echo htmlspecialchars($serv["titulo"]); ?></h2>

         <div class="mensajeria-header">
    <div class="opciones">
        <a href="servicios.php"><i class="fa-solid fa-calendar-check"></i> Ver servicios</a> |
  <?php  if ($_SESSION["rol"] === "proveedor") {
        echo '<a href="principalProveedor.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "cliente") {
      echo '<a href="principalCliente.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "admin") {
          echo '<a href="principalAdmin.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    }
    ?>
   
    

    <?php if (isset($_GET["error"])): ?>
        <?php 
            switch ($_GET["error"]) {
                case "fecha_ocupada":
                    echo '<p style="color:red;">Este servicio ya está reservado para esta fecha.</p>';
                    break;
                case "datos_invalidos":
                    echo '<p style="color:red;"> Debes completar los campos correctamente</p>';
                    break;
                case "servicio_inexistente":
                      echo '<p style="color:red;">El servicio que seleccionaste no existe</p>';
                    break;

                case "fecha_pasada":
                      echo '<p style="color:red;">No puedes reservar una fecha que ya pasó.</p>';
                    break;
    
            }
            ?>
            <?php endif; ?>
    

    <form method="POST" action="../Controlador/reservaControlador.php?accion=guardar">
        <!-- mantenemos el id del servicio -->
        <input type="hidden" name="idServicio" value="<?php echo $idServicio; ?>">

        <label>Fecha (AAAA-MM-DD):</label><br>
        <input type="date" name="fecha" required min="<?php echo date('Y-m-d'); ?>"><br><br>

        <label>Calle:</label><br>
        <input type="text" name="calle" required><br><br>

        <label>Número :</label><br>
        <input type="number" name="numero" required><br><br>

        <label>Descripción del trabajo:</label><br>
         <textarea name="detalle" required placeholder="Describe qué necesitas..."></textarea><br><br>


        <button type="submit">Confirmar reserva</button>
    </form>

    
</body>
</html>