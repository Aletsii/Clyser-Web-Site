<?php
require_once "conexion.php";

class Calificacion {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConexion();
        $this->conn->set_charset("utf8mb4");
    }

    public function registrarCalificacion(int $idReserva, int $puntuacion, string $comentario) : array {
        $sql = "INSERT INTO calificacion (idReserva, puntuacion, comentario, fechaCalificacion)
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bind_param("iis", $idReserva, $puntuacion, $comentario);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return ["ok" => false, "error" => $err];
        }
        $stmt->close();
        return ["ok" => true];
    }

public function listarPorProveedor(int $idProveedor): array {
    $sql = "SELECT 
                c.idCalificacion,
                r.idCliente,
                c.puntuacion,
                c.comentario,
                c.fechaCalificacion,
                p.nombre
            FROM calificacion c
            INNER JOIN reserva r ON c.idReserva = r.idReserva
            INNER JOIN persona p ON p.idUsuario = r.idCliente
            WHERE r.idProveedor = ?
            ORDER BY c.fechaCalificacion DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idProveedor);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}


    public function obtenerPromedio(int $idProveedor) : ?float {
        $sql = "SELECT AVG(c.puntuacion) AS promedio
                FROM calificacion c
                JOIN reserva r ON c.idReserva = r.idReserva
                WHERE r.idProveedor = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if (!$row || $row["promedio"] === null) return null;
        return round((float)$row["promedio"], 1);
    }

   public function yaCalificada(int $idReserva): bool {
    $sql = "SELECT COUNT(*) AS total FROM calificacion WHERE idReserva = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idReserva);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return ($res["total"] > 0);
}

//muestra las calificaciones que hace el cliente
public function listarPorCliente(int $idCliente): array {
    $sql = "SELECT 
                c.idCalificacion,
                c.puntuacion,
                c.comentario,
                c.fechaCalificacion,
                s.titulo AS servicio,
                p.nombre AS proveedor
            FROM calificacion c
            JOIN reserva r ON c.idReserva = r.idReserva
            JOIN servicio s ON r.idServicio = s.idServicio
            JOIN persona p  ON r.idProveedor = p.idUsuario
            WHERE r.idCliente = ?
            ORDER BY c.fechaCalificacion DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}



}
