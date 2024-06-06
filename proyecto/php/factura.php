<?php
require_once("conexion.php");
class Factura extends Conexion
{
    /*Esta variable almacenara la conexion a la base de datos*/
    protected $db;
    protected $productos = array();
    /*El constructor se ejecutara autamaticamente cada vez que se llame a la clase Factura*/

    public function __construct()
    {
        $this->db = new Conexion();
        $this->db = $this->db->conexiondb();
    }


    public function cargarFactura()
    {
        $json = file_get_contents("factura.json");
        $this->productos = json_decode($json, true);
    }
    public function insertarFactura()
    {
        $sql = "INSERT INTO productos_factura(codigo_producto, nombre_producto, unidades_caja, caja_facturada, 
            precio_unidades, fecha_entrada) VALUES(:cod, :nom, :unid, :caja, :precio, :fecha)";
        $insert = $this->db->prepare($sql);
        foreach ($this->productos as $producto) {
            $insert->bindParam(":cod", $producto['codigo_producto'], PDO::PARAM_INT);
            $insert->bindParam(":nom", $producto['nombre_producto'], PDO::PARAM_STR);
            $insert->bindParam(":unid", $producto['unidades_caja'], PDO::PARAM_INT);
            $insert->bindParam(":caja", $producto['caja_facturada'], PDO::PARAM_INT);
            $insert->bindParam(":precio", $producto['precio_unidades'], PDO::PARAM_STR);
            $insert->bindParam(":fecha", $producto['fecha_entrada'], PDO::PARAM_STR);
            $insert->execute();
        }
    }

    public function mostrarProductosFactura()
    {

        $sql = "SELECT codigo_producto, nombre_producto, unidades_caja, caja_facturada, precio_unidades 
        FROM productos_factura WHERE caja_facturada > 0";
        $resultado = $this->db->prepare($sql);
        $resultado->execute();
        $datos = array();
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {

            $datos[] = [
                'codigo_producto' => $registro['codigo_producto'],
                'nombre_producto' => $registro['nombre_producto'],
                'unidades_caja' => $registro['unidades_caja'],
                'caja_facturada' => $registro['caja_facturada'],
                'precio_unidades' => $registro['precio_unidades'],
            ];
        }
        echo json_encode($datos);
    }
    
    
}
