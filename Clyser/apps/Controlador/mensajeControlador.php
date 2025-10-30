<?php
session_start();
require_once "../Modelo/Mensaje.php";

$accion = $_GET["accion"] ?? $_POST["accion"] ?? "";
$mensajeModel = new Mensaje();

switch ($accion) {

   
    case "enviar":
        if (!isset($_SESSION["idUsuario"])) {
            header("Location: ../Vista/index.php?error=no_logueado");
            exit;
        }

        $idRemitente    = (int)$_SESSION["idUsuario"];
        $rolRemitente   = $_SESSION["rol"] ?? "";
        $idDestinatario = (int)($_POST["idDestinatario"] ?? 0);
        $asunto         = trim($_POST["asunto"] ?? "");
        $contenido      = trim($_POST["contenido"] ?? "");

        // Validaciones básicas
        if ($idDestinatario <= 0 || $contenido === "") {
            header("Location: ../Vista/mensajeria.php?error=datos_invalidos");
            exit;
        }

        // Enviar mensaje
        $res = $mensajeModel->enviarMensaje($idRemitente, $rolRemitente, $idDestinatario, $asunto, $contenido);

        if (!$res["ok"]) {
            header("Location: ../Vista/mensajeria.php?id=$idDestinatario&error=" . urlencode($res["error"]));
            exit;
        }

        header("Location: ../Vista/mensajeria.php?id=$idDestinatario&msg=mensaje_enviado");
        exit;

    
    case "listar":
        if (!isset($_SESSION["idUsuario"])) {
            header("Location: ../Vista/index.php?error=no_logueado");
            exit;
        }

        $idUsuario  = (int)$_SESSION["idUsuario"];
        $rolUsuario = $_SESSION["rol"] ?? "";
        $mensajes   = $mensajeModel->listarBandeja($idUsuario, $rolUsuario);

        header("Content-Type: application/json");
        echo json_encode($mensajes);
        exit;

    

    case "ver":
        if (!isset($_SESSION["idUsuario"])) {
            header("Location: ../Vista/index.php?error=no_logueado");
            exit;
        }

        $idMensaje = (int)($_GET["idMensaje"] ?? 0);
        $idUsuario = (int)$_SESSION["idUsuario"];
        $rolUsuario = $_SESSION["rol"] ?? "";

        $msg = $mensajeModel->obtenerMensaje($idMensaje, $idUsuario, $rolUsuario);
        if (!$msg) {
            header("Location: ../Vista/mensajes.php?error=no_encontrado");
            exit;
        }

        header("Content-Type: application/json");
        echo json_encode($msg);
        exit;

   
    default:
        header("Location: ../Vista/mensajes.php");
        exit;
}
?>
