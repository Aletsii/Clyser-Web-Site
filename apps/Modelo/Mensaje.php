<?php 
require_once "conexion.php";

class Mensaje {
    private $conn;
    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConexion();
        $this->conn->set_charset("utf8mb4");
    }

    public function enviarMensaje(int $idRemitente, string $rolRemitente, int $idDestinatario, string $asunto, string $contenido) : array {
    if ($idDestinatario <= 0 || $asunto === "" || $contenido === "") {
        return ["ok" => false, "error" => "datos_invalidos"];
    }

    
    $sql = "INSERT INTO mensaje (idRemitente, idDestinatario, asunto, contenido)
            VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiss", $idRemitente, $idDestinatario, $asunto, $contenido);

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return ["ok" => false, "error" => $err];
    }

    $stmt->close();
    return ["ok" => true];
}

public function listarBandeja(int $idUsuario, string $rolUsuario): array {
    
    $sql =   $sql = "SELECT m.idMensaje, m.asunto, m.fecha,
                       p.nombre AS nombreRemitente,
                       p.fotoPerfil AS fotoPerfilRemitente
                FROM mensaje m
                JOIN persona p ON p.idUsuario = m.idRemitente
                WHERE m.idDestinatario = ?
                ORDER BY m.fecha DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $data ?: [];
}

  

public function obtenerMensaje(int $idMensaje, int $idUsuario, string $rolUsuario) : ?array {
       $sql = "SELECT m.idMensaje, m.asunto, m.contenido, m.fecha,
                       r.idUsuario AS idRemitente, r.nombre AS nombreRemitente,
                       r.fotoPerfil AS fotoPerfilRemitente,
                       d.idUsuario AS idDestinatario, d.nombre AS nombreDestinatario,
                       d.fotoPerfil AS fotoPerfilDestinatario
                FROM mensaje m
                JOIN persona r ON r.idUsuario = m.idRemitente
                JOIN persona d ON d.idUsuario = m.idDestinatario
                WHERE m.idMensaje = ?
                AND (m.idRemitente = ? OR m.idDestinatario = ?)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $idMensaje, $idUsuario, $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;

}
public function listarEnviados(int $idUsuario) : array {
    $sql = "SELECT m.idMensaje, m.asunto, m.fecha,
                       p.nombre AS nombreDestinatario
                FROM mensaje m
                JOIN persona p ON p.idUsuario = m.idDestinatario
                WHERE m.idRemitente = ?
                ORDER BY m.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data ?: [];
}
public function marcarComoLeido(int $idMensaje): bool {
    $sql = "UPDATE mensaje SET leido = 1 WHERE idMensaje = ?";
      $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idMensaje);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function contarNoLeidos(int $idUsuario, string $rolUsuario): int {
    if ($rolUsuario === "proveedor") {
        $sql = "SELECT COUNT(*) FROM mensaje WHERE idDestinatario = ? AND leido = 0";
     } else {
        $sql = "SELECT COUNT(*) FROM mensaje WHERE idDestinatario = ? AND leido = 0";
     }
$stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total;
}




}
