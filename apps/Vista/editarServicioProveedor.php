<?php
session_start();
require_once "../Modelo/Servicio.php";

if(!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "proveedor") {
    header("Location: ../../public/index.php?error=no_autorizado");
    exit;
}

$id = (int)($_GET["id"] ?? 0);
$idProveedor = $_SESSION["idUsuario"];

$conn = (new Conexion())->getConexion();
$stmt = $conn->prepare("SELECT * FROM servicio WHERE idServicio = ? AND idProveedor = ?");
$stmt->bind_param("ii", $id, $idProveedor);
$stmt->execute();
$res = $stmt->get_result();
$servicio = $res->fetch_assoc();
$stmt->close();

if(!$servicio) {
    die("Servicio no encontrado o no es tuyo.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar servicio | Proveedor</title>
<link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<h2>Editar servicio (Proveedor)</h2>

<form action="../Controlador/servicioControlador.php?accion=editar&id=<?= $id ?>" method="POST">

<label>Título:</label>
<input type="text" name="titulo" value="<?= htmlspecialchars($servicio['titulo']) ?>" required>

<label>Descripción:</label>
<textarea name="descripcion" rows="4" required><?= htmlspecialchars($servicio['descripcion']) ?></textarea>

<label>Precio (UYU):</label>
<input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($servicio['precio']) ?>" required>

label>Categoría:</label>
    <select name="categoria">
        <option value="informatica">Informatica</option>
        <option value="carpinteria">Carpinteria</option>
        <option value="limpieza">Limpieza</option>
        <option value="mecanica">Mecanica</option>
        <option value="construccion">Construccion</option>
        <option value="pintureria">Pintureria</option>
        <option value="plomeria">Plomeria</option>
        <option value="otros">Otros</option>
        
    </select>

<button type="submit" class="btn-ver">Guardar cambios</button>
</form>

</body>
</html>
