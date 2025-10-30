<?php
session_start();
require_once "../Modelo/Reserva.php";
require_once "../Modelo/Servicio.php";

$accion = $_GET["accion"] ?? $_POST["accion"] ?? "";
$reservaModel = new Reserva();
$servicioModel = new Servicio();

switch ($accion) {

    
    
    case "guardar":
        if (!isset($_SESSION["idUsuario"])) {
            header("Location: ../../index.php?error=no_logueado");
            exit;
        }

        $idServicio = (int)($_POST["idServicio"] ?? 0);
        $fecha      = trim($_POST["fecha"] ?? "");
        $calle      = trim($_POST["calle"] ?? "");
        $numero     = (int)($_POST["numero"] ?? 0);
        $idCliente  = $_SESSION["idUsuario"];

        if ($idServicio <= 0 || $fecha === "" || $calle === "" || $numero === 0) {
            header("Location: ../Vista/reservas.php?id=$idServicio&error=datos_invalidos");
            exit;
        }

        $serv = $servicioModel->obtenerServicioPorId($idServicio);
        if (!$serv) {
            header("Location: ../Vista/servicios.php?error=servicio_inexistente");
            exit;
        }

        $idProveedor = (int)$serv["idProveedor"];

        $fecha = $_POST["fecha"];
        $hoy = date('Y-m-d');
        if ($fecha < $hoy) {
            header("Location: ../Vista/reservas.php?id=$idServicio&error=fecha_pasada");
            exit;
        }

        if (!$reservaModel->estaDisponible($idServicio, $fecha)) {
            header("Location: ../Vista/reservas.php?id=$idServicio&error=fecha_ocupada");
            exit;
        }

        $numero = (int)($_POST["numero"] ?? 0);
        if ($numero <= 0) {
            header("Location: ../Vista/reservas.php?id=$idServicio&error=numero_invalido");
            exit;
         }


        $res = $reservaModel->guardarReserva($idServicio, $idCliente, $idProveedor, $fecha, $calle, $numero);
        header("Location: ../Vista/principalCliente.php?msg=" . ($res["ok"] ? "reserva_creada" : "error"));
        exit;


    case "cambiarEstado":
        if (($_SESSION["rol"] ?? "") !== "proveedor") {
            header("Location: ../Vista/principalProveedor.php?error=no_autorizado");
            exit;
        }

        $idReserva = (int)($_POST["id"] ?? 0);
        $estado = $_POST["estado"] ?? "";

        if ($idReserva <= 0 || !in_array($estado, ["aceptada", "rechazada"])) {
            header("Location: ../Vista/reservasRecibidas.php?error=parametros_invalidos");
            exit;
        }

        $ok = $reservaModel->cambiarEstado($idReserva, $estado);
        header("Location: ../Vista/reservasRecibidas.php?msg=" . ($ok ? "estado_actualizado" : "error"));
        exit;


    case "finalizar":
        if (($_SESSION["rol"] ?? "") !== "proveedor") {
            header("Location: ../Vista/principalProveedor.php?error=no_autorizado");
            exit;
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id <= 0) {
            header("Location: ../Vista/reservasRecibidas.php?error=id_invalido");
            exit;
        }

        $ok = $reservaModel->marcarFinalizada($id);
        header("Location: ../Vista/reservasRecibidas.php?msg=" . ($ok ? "finalizada_ok" : "error"));
        exit;


    default:
        header("Location: ../Vista/servicios.html");
        exit;
}
?>
