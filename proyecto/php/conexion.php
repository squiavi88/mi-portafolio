<?php
class Conexion
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "check_system";
    private $connect;

    public function __construct()
    {
        try {
            $this->connect = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=utf8", $this->user, $this->pass);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /*Creamos un objeto ERROR, ademas nos facilita detectar los errores posibles*/
        } catch (Exception $e) {

            echo "La linea del error es: " . $e->getLine();
        }
    }
    public function conexiondb()
    {
        return $this->connect;
    }
}