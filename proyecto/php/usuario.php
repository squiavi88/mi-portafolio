<?php

include_once('conexion.php');
class Usuario extends Conexion
{

    private $db;
    private $usuario;
    private $pass;

    public function __construct($usuario, $pass)
    {

        $this->db = new Conexion();
        $this->db = $this->db->conexiondb();
        $this->usuario = $usuario;
        $this->pass = $pass;
    }

    public function login()
    {

        $registro = array();
        $error = 'error';
        $sql = "SELECT usuario.nombre, cargo.descripcion, cargo.id FROM usuario 
        inner JOIN cargo ON usuario.id_cargo = cargo.id WHERE usuario.usuario = 
        '$this->usuario' AND usuario.pass = '$this->pass';
        ";
        $result = $this->db->prepare($sql);
        $result->execute();
        $registro[] = $result->fetch(PDO::FETCH_ASSOC);
        $count = $result->rowCount();
        if ($count === 0) {
            echo json_encode($error);
        } else {
            echo json_encode($registro);
        }
        
    }
}
