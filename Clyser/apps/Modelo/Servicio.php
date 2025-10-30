<?php
require_once "conexion.php";

class Servicio {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConexion();
        $this->conn->set_charset("utf8mb4");
    }

    public function guardarServicio(int $idProveedor, string $titulo, string $descripcion, float $precio, ?string $categoria) : array {
        $sql = "INSERT INTO servicio (idProveedor, titulo, descripcion, precio, categoria)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issds", $idProveedor, $titulo, $descripcion, $precio, $categoria);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return ["ok" => false, "error" => $err];
        }

        $stmt->close();
        return ["ok" => true];
    }

        public function listarServicios() : array {
            $sql = "SELECT s.idServicio, s.idProveedor, s.titulo, s.descripcion, s.precio, s.categoria, s.fechaServicio,
                     p.nombre AS proveedor, p.fotoPerfil
                     FROM servicio s
                     INNER JOIN persona p ON p.idUsuario = s.idProveedor
                     ORDER BY s.fechaServicio DESC";

        
            $res = $this->conn->query($sql);
            return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        
    }

    public function buscarPorCategoria(string $categoria) : array{
        $sql = "SELECT s.idServicio, s.idProveedor, s.titulo, s.descripcion, s.precio, s.categoria, s.fechaServicio,
                     p.nombre AS proveedor, p.fotoPerfil
                     FROM servicio s
                     INNER JOIN persona p ON p.idUsuario = s.idProveedor
                     WHERE s.categoria = ?
                     ORDER BY s.fechaServicio DESC";
        $stmt = $this->conn->prepare($sql);
       $stmt->bind_param("s", $categoria);
       $stmt->execute();
       $res = $stmt->get_result();
       $data = $res->fetch_all(MYSQLI_ASSOC);
       $stmt->close();
       return $data;
    }

    public function listarCategorias() : array {
    $sql = "SELECT DISTINCT categoria FROM servicio 
            WHERE categoria IS NOT NULL AND categoria <> ''";
    $res = $this->conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

public function obtenerServicioPorId(int $idServicio) : ?array {
    $sql = "SELECT idServicio, idProveedor, titulo, precio, categoria
            FROM servicio WHERE idServicio = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idServicio);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

public function listarPorProveedor(int $idProveedor) : array {
    $sql = "SELECT idServicio, titulo, descripcion, precio, categoria, fechaServicio
          FROM servicio
        WHERE idProveedor = ?
        ORDER BY fechaServicio DESC";

             $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
   
}
public function eliminarServicio(int $idServicio, int $idProveedor): bool {
    $sql = "DELETE FROM servicio WHERE idServicio = ? AND idProveedor = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $idServicio, $idProveedor);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
}
