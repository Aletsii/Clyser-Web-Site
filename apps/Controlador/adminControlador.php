<?php 
session_start();
require_once "../Modelo/Usuario.php";
require_once "../Modelo/Servicio.php";

if (!isset($_SESSION["idUsuario"]) || $_SESSION["rol"] !== "admin") {
   header("Location: ../Vista/index.php?error=no_autorizado");
    exit;
 }

 $accion = $_GET["accion"] ?? "";
 $id = (int)($_GET["id"] ?? 0);

 $conn = (new Conexion())->getConexion();
 switch($accion) {

    case "eliminarUsuario":

        //elimino mensajes relacionado con el usuario
       $stmt = $conn->prepare("DELETE FROM mensaje WHERE idRemitente = ? OR idDestinatario = ?");
       $stmt->bind_param("ii", $id, $id);
       $stmt->execute();
       $stmt->close();

       //tambien las reservas
       $stmt = $conn->prepare("DELETE FROM reserva WHERE idCliente = ? OR idProveedor = ?");
       $stmt->bind_param("ii", $id, $id);
       $stmt->execute();
       $stmt->close();

        $stmt = $conn->prepare("DELETE FROM persona WHERE idUsuario = ? AND rol <> 'admin'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../Vista/gestionUsuarios.php?msg=usuario_eliminado");
        exit;



    case "eliminarServicio":
//primero elimino las reservas del servicio
        $stmt = $conn->prepare("DELETE FROM reserva WHERE idServicio = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        //ahora si el servicio porque sino da error
        $stmt = $conn->prepare("DELETE FROM servicio WHERE idServicio = ?");
         $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../Vista/gestionServicios.php?msg=servicio_eliminado");
        exit;

    case "editarServicio": 
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $titulo = trim($_POST["titulo"] ?? "");
             $descripcion = trim($_POST["descripcion"] ?? "");
              $precio = (float)($_POST["precio"] ?? 0);
               $categoria = trim($_POST["categoria"] ?? "");

               $stmt = $conn->prepare("UPDATE servicio SET titulo=?, descripcion=?, precio=?, categoria=? WHERE idServicio=?");
               $stmt->bind_param("ssdsi", $titulo, $descripcion, $precio, $categoria, $id);
               $stmt->execute();
               $stmt->close();

               header("Location: ../Vista/gestionServicios.php?msg=servicio_editado");
               exit;

      } else {
        header("Location: ../Vista/editarServicio.php?=$id");
        exit;
      }
      default:
      header("Location: ../Vista/principalAdmin.php");
      exit;
    
 }?>