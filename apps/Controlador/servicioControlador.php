<?php
session_start();
require_once "../Modelo/Servicio.php";

if (!isset($_SESSION["idUsuario"])) {
    header("Location: ../Vista/index.php?error=no_logueado");
    exit;
}

$accion = $_GET["accion"] ?? $_POST["accion"] ?? "";


$servicioModel = new Servicio();

switch ($accion) {
    case "guardar":
        // solo proveedores publican
        if (($_SESSION["rol"] ?? "") !== "proveedor") {
            header("Location: ../Vista/principalProveedor.php?error=solo_proveedor");
            exit;
        }

        $titulo      = trim($_POST["titulo"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $precio      = isset($_POST["precio"]) ? (float)$_POST["precio"] : 0;
        $categoria   = trim($_POST["categoria"] ?? "");
        $idProveedor = (int)$_SESSION["idUsuario"];

        if ($titulo === "" || $descripcion === "" || $precio <= 0) {
            header("Location: ../Vista/publicarServicio.php?error=datos_invalidos");
            exit;
        }

  $res = $servicioModel->guardarServicio($idProveedor, $titulo, $descripcion, $precio, $categoria);
        if (!$res["ok"]) {
            header("Location: ../Vista/publicarServicio.php?error=" . $res["error"]);
            exit;
        }

        header("Location: ../Vista/principalProveedor.php?msg=servicio_publicado");
        exit;

        case "mostrar":
    header("Content-Type: application/json; charset=utf-8");

    $categoria = $_GET["categoria"] ?? "";
    if ($categoria !== "") {
        $servicios = $servicioModel->buscarPorCategoria($categoria);
    } else {
        $servicios = $servicioModel->listarServicios();
    }

    echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    exit;


            case "categorias":
    header("Content-Type: application/json; charset=utf-8");
    $cats = $servicioModel->listarCategorias();
    echo json_encode($cats, JSON_UNESCAPED_UNICODE);
    exit;



        case "eliminar":
            if (!isset($_SESSION["idUsuario"])) {
                header("Location: ../Vista/index.php?error=no_logueado");
                exit;
            }
            $idProveedor = $_SESSION["idUsuario"];
            $idServicio = (int)($_GET["id"] ?? 0);

            if ($idServicio > 0) {
                $servicioModel = new Servicio();
            $ok = $servicioModel->eliminarServicio($idServicio, $idProveedor);
            if ($ok) {
                header("Location: ../vista/perfilProveedor.php?msg=servicio_eliminado");

            }else{
                header("Location: ../Vista/perfilProveedor.php?error=no_autorizado");
            }
        }exit;

       case "editar":
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int)($_GET["id"] ?? 0);
        $idProveedor = (int)$_SESSION["idUsuario"];

        $titulo = trim($_POST["titulo"]);
        $descripcion = trim($_POST["descripcion"]);
        $precio = (float)$_POST["precio"];
        $categoria = trim($_POST["categoria"]);
        $conn = (new Conexion())->getConexion();

        $stmt = $conn->prepare("UPDATE servicio 
                                SET titulo=?, descripcion=?, precio=?, categoria=?
                                WHERE idServicio=? AND idProveedor=?");

        $stmt->bind_param("ssdsii", $titulo, $descripcion, $precio, $categoria, $id, $idProveedor);
        $stmt->execute();
        $stmt->close();

        header("Location: ../Vista/perfilProveedor.php?msg=servicio_editado");
        exit;
    }
break;


    default:
        header("Location: ../Vista/principalProveedor.php");
        exit;

}
