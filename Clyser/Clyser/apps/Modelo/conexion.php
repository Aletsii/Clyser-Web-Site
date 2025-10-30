<?php
class Conexion {
    private $host = "localhost";  
    private $usuario = "root";   
    private $clave = "";           
    private $bd = "clyser";       
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->usuario, $this->clave, $this->bd);

        // Verifica si hubo error
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Método para obtener la conexión
    public function getConexion() {
        return $this->conn;
    }

    
    
}
?>
