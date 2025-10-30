<?php
require_once "conexion.php";

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConexion();
        $this->conn->set_charset("utf8mb4");    
    }

    // Función para registrar usuarios
    public function registrarUsuario(string $nombre, string $correo, string $passwordPlano, string $rol) : array {
        // Validar si el correo ya existe
        $sql = "SELECT idUsuario FROM persona WHERE correoElectronico = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0){
            $stmt->close();
            return ["ok" => false, "error" => "correo_existente"];
        }
        $stmt->close();

        // Encriptar contraseña para mayor seguridad mediante hashin
        $hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

        $sql = "INSERT INTO persona (nombre, `contraseña`, correoElectronico, rol)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $hash, $correo, $rol);

        if(!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return ["ok" => false, "error" => $err];
        }

        $nuevoId = $stmt->insert_id;
        $stmt->close();
        return ["ok" => true, 
    
                  "usuario" => [
                    "idUsuario" => $nuevoId,
                    "nombre" => $nombre,
                    "correo" => $correo,
                    "rol" => $rol,
                    "fotoPerfil" => null,
                    "descripcion" => null,
                  ]
                ];
    }

    // Login de usuario
    public function loginUsuario(string $correo, string $passwordPlano) : array {
        $sql = "SELECT idUsuario, nombre, `contraseña`, rol, fotoPerfil, descripcion 
                FROM persona WHERE correoElectronico = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows === 0){
            $stmt->close();
            return ["ok" => false, "error" => "usuario_no_encontrado"];
        }

        $row = $res->fetch_assoc();
        $stmt->close();

        $hashDB = $row["contraseña"];
        $valido = password_verify($passwordPlano, $hashDB) ;

        if (!$valido) {
            return ["ok" => false, "error" => "password_incorrecto"];
        }
                   
        return [
            "ok" => true,
            "usuario" => [
                "idUsuario"   => (int)$row["idUsuario"],
                "nombre"      => $row["nombre"],
                "rol"         => $row["rol"],
                "correo"      => $correo,
                "fotoPerfil"  => $row["fotoPerfil"],
                "descripcion" => $row["descripcion"]
            ]
        ];
    }

    // Datos del perfil
    public function obtenerUsuarioPorId(int $idUsuario) : ?array {
        $sql = "SELECT idUsuario, nombre, correoElectronico AS correo, rol, fechaRegistro, fotoPerfil, descripcion
                FROM persona WHERE idUsuario = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


public function actualizarFoto(int $idUsuario, string $nombreArchivo) : bool {
    $sql = "UPDATE persona SET fotoPerfil = ? WHERE idUsuario = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $nombreArchivo, $idUsuario);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}


}
