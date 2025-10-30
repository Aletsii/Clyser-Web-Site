<?php
session_start();
require_once "../Modelo/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
     
if (isset($_GET["accion"]) && $_GET["accion"] === "logout") {
    session_unset();
    session_destroy();

     header("Location: ../Vista/index.php?logout=1");
        exit;

   }
    
     header("Location: ../../index.php");
    exit;
}

$accion = $_POST["accion"] ?? "";
$usuarioModel = new Usuario();

switch ($accion) {
    case "registrar":
        $nombre = trim($_POST["nombre"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $password = $_POST["password"] ?? "";
        $rol = $_POST["rol"] ?? "cliente";

    
        $res = $usuarioModel->registrarUsuario($nombre, $correo, $password, $rol);

        if (!$res["ok"]) {
            $err = $res["error"];
            header("Location: ../../index.php?error=" . $err);
            exit;
        }

    
        $u = $res["usuario"];

        $_SESSION["idUsuario"] = $u["idUsuario"];
        $_SESSION["nombre"] = $u["nombre"];
        $_SESSION["rol"] = $u["rol"];
        $_SESSION["correo"] = $u["correo"];
        $_SESSION["fotoPerfil"] = $u["fotoPerfil"];
        $_SESSION["descripcion"] = $u["descripcion"];

        if ($u["rol"] === "cliente") {
            header("Location: ../Vista/principalCliente.php");
        } elseif ($u["rol"] === "proveedor") {
            header("Location: ../Vista/principalProveedor.php");
        } elseif ($u["rol"] === "admin") {
            header("location: ../Vista/principalAdmin.php");
        }
        exit;

    case "login":
        $correo = trim($_POST["correo"] ?? "");
        $password = $_POST["password"] ?? "";

        
        $res = $usuarioModel->loginUsuario($correo, $password);

        if (!$res["ok"]) {
            $err = $res["error"];
            header("Location: ../../index.php?error=" . $err);
            exit;
        }

        $u = $res["usuario"];

        $_SESSION["idUsuario"] = $u["idUsuario"];
        $_SESSION["nombre"] = $u["nombre"];
        $_SESSION["rol"] = $u["rol"];
        $_SESSION["correo"] = $u["correo"];
        $_SESSION["fotoPerfil"] = $u["fotoPerfil"];
        $_SESSION["descripcion"] = $u["descripcion"];

        if ($u["rol"] === "cliente") {
            header("Location: ../Vista/principalCliente.php");
        } elseif ($u["rol"] === "proveedor") {
            header("Location: ../Vista/principalProveedor.php");
        } elseif ($u["rol"] === "admin") {
            header("location: ../Vista/principalAdmin.php");
        }
        exit;
    
    default:
        header("Location: ../Vista/index.php");
        exit;
}

