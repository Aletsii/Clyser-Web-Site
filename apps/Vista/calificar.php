<?php 
session_start();
require_once "../Modelo/Calificacion.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}
$idReserva = (int)($_GET["idReserva"] ?? 0);
$calificarModel = new Calificacion();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $puntuacion = (int)$_POST["puntuacion"];
    $comentario = trim($_POST["comentario"]);


    //evita calificaciones duplicadas
    if ($calificarModel->yaCalificada($idReserva)) {
        header("Location: reservasCliente.php?msg=ya_calificada");
        exit;
    }
    
    if($idReserva > 0 && $puntuacion >= 1 && $puntuacion <=5) {
        $ok = $calificarModel->registrarCalificacion($idReserva, $puntuacion, $comentario);
        if($ok) {
            header("Location: reservasCliente.php?msg=calificacion_ok");
            exit;
        } else{
            $error = "No se pudo registrar la calificacion";
        }
    }
   


}
     ?>
     <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificar servicio</title>
    <link rel="icon" type="image/png" href="../../public/img/C.png">
      <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <h2>Calificar servicio</h2>
    <form method="POST">
        <input type="hidden" name="idReserva" value="<?php echo $idReserva; ?>">
    <label for="puntuacion">Puntuación:</label>
        <select name="puntuacion" required>
            <option value="">Seleccione...</option>
            <option value="5">⭐⭐⭐⭐⭐ (Excelente)</option>
            <option value="4">⭐⭐⭐⭐ (Muy bueno)</option>
            <option value="3">⭐⭐⭐ (Bueno)</option>
            <option value="2">⭐⭐ (Regular)</option>
            <option value="1">⭐ (Malo)</option>
        </select>

        <label for="comentario">Comentario:</label>
        <textarea name="comentario" rows="4" placeholder="Escribe tu opinion" required></textarea>

        <button type="submit">Enviar calificación</button>
    </form>
    </body>
    </html>