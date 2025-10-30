<?php 
session_start();
require_once "../Modelo/Usuario.php";
require_once "../Modelo/Calificacion.php";
require_once "../Modelo/Servicio.php";
require_once "../Modelo/Mensaje.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php?error=no_logueado");
    exit;
}

$idUsuario = (int)($_GET["id"] ?? 0);
$usuarioModel = new Usuario();
$calificacionModel = new Calificacion();
$servicioModel = new Servicio();

$rolUsuario = $_SESSION["rol"];
$mensajeModel = new Mensaje();
$nuevos = $mensajeModel->contarNoLeidos($idUsuario, $rolUsuario);

$usuario = $usuarioModel->obtenerUsuarioPorId($idUsuario);
if (!$usuario){
    die("Usuario no encontrado.");
}

$rol = strtolower($usuario["rol"] ?? "cliente");

$fotoPerfil = "../../public/img/avatarPredeterminada.png";
if (!empty($usuario["fotoPerfil"])) {
    $fotoPerfil = "../../public/img/uploads/" . htmlspecialchars($usuario["fotoPerfil"]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($usuario["nombre"]); ?></title>
  <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" type="/image/png" href="../../public/img/C.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<main class="perfil-container fade-in-up">

    <div class="mensajeria-header">
        <div class="opciones">
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

    
    <div class="perfil-card">
     <div class="perfil-foto">   
        <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil">
     </div>

        <h2><?php echo htmlspecialchars($usuario["nombre"]); ?></h2>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario["correo"]); ?></p>
        <p class="rol"><?php echo ucfirst($rol); ?></p>

        <?php if ($rol === "proveedor"): ?>
             <hr class="separador">
    
            <h3 class="subtitulo">⭐ Calificaciones recibidas</h3>

            <?php 
            $calificaciones = $calificacionModel->listarPorProveedor($idUsuario);
            if (empty($calificaciones)): ?>
                <p class="mensaje-vacio">Este proveedor no ha recibido reseñas.</p>
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
        
       <hr class="separador">

        <div class="ver-servicios">
            <h3 class="subtitulo">Servicios ofrecidos</h3>
            <?php 
            $servicios = $servicioModel->listarPorProveedor($idUsuario);
            if (empty($servicios)): ?>
                <p class="mensaje-vacio">No tiene servicios publicados.</p>
            <?php else: ?>
                <div class="tarjeta-lista">
                    <?php foreach ($servicios as $s): ?>
                        <div class="tarjeta-item">
                            <h4><?php echo htmlspecialchars($s["titulo"]); ?></h4>
                            <p><i class="fa-solid fa-align-left"></i> <?php echo htmlspecialchars($s["descripcion"]); ?></p>
                            <p><i class="fa-solid fa-dollar-sign"></i> <strong>Precio por hora:</strong> <?php echo htmlspecialchars($s["precio"]); ?> UYU</p>
                            <p><i class="fa-solid fa-layer-group"></i> <strong>Categoría:</strong> <?php echo htmlspecialchars($s["categoria"]); ?></p>
                        </div>    
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
    <?php else: ?>
       <p class="mensaje-vacio">Este usuario no es proveedor, por lo que no tiene servicios.</p>
    <?php endif; ?>

    
</div>
                    </main>
</body>
</html>
