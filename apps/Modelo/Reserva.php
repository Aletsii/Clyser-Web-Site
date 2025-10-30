<?php
require_once "conexion.php";

class Reserva {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConexion();
        $this->conn->set_charset("utf8mb4");
    }

    // Guarda reserva pendiente
    public function guardarReserva( int $idServicio, int $idCliente, int $idProveedor, string $fecha, string $calle, ?int $numero, string $detalle ) : array {
        $sql = "INSERT INTO reserva (idServicio, idCliente, idProveedor, fecha, estadoReserva, calle, numero, detalle)
                VALUES (?, ?, ?, ?, 'pendiente', ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiissis", $idServicio, $idCliente, $idProveedor, $fecha, $calle, $numero, $detalle);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return ["ok" => false, "error" => $err];
        }
        $stmt->close();
        return ["ok" => true];
    }

public function mostrarReservaCliente(int $idCliente) : array{
    $sql = "SELECT
             r.idReserva, r.fecha, r.estadoReserva, r.calle, r.numero, r.detalle,
                    s.idServicio, s.titulo, s.categoria, s.precio,
                    p.nombre AS proveedor,
                    p.fotoPerfil
                FROM reserva r
                JOIN servicio s   ON r.idServicio = s.idServicio
                JOIN persona  p   ON r.idProveedor = p.idUsuario
                WHERE r.idCliente = ?
                ORDER BY r.fecha DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}

public function mostrarReservaProveedor(int $idProveedor) : array {
        $sql = "SELECT
                    r.idReserva, r.fecha, r.estadoReserva, r.calle, r.numero, r.detalle,
                    s.idServicio, s.titulo, s.categoria, s.precio,
                    c.nombre AS cliente,
                    c.fotoPerfil
                FROM reserva r
                JOIN servicio s   ON r.idServicio = s.idServicio
                JOIN persona  c   ON r.idCliente   = c.idUsuario
                WHERE r.idProveedor = ?
                ORDER BY r.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $res  = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;}


public function estaDisponible(int $idServicio, string $fecha) : bool {
    $sql = "SELECT COUNT(*) AS total FROM reserva
            WHERE idServicio = ? AND fecha = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("is", $idServicio, $fecha);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return ($res["total"] == 0);
}

public function cambiarEstado(int $idReserva, string $nuevoEstado) : bool {
    $sql = "UPDATE reserva SET estadoReserva = ? WHERE idReserva = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $nuevoEstado, $idReserva);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function marcarFinalizada(int $idReserva): bool {
    
    $sql = "UPDATE reserva SET estadoReserva = 'finalizada' WHERE idReserva = ?";
  $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idReserva);
    $res = $stmt->execute();
    $stmt->close();
    
    return $res;
}


}
