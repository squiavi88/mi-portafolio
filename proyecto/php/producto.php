<?php
require_once("factura.php");

class Producto extends Factura
{
    protected $codigo;
    protected $bulto;

    public function __construct($codigo, $bulto)
    {
        parent::__construct();
        $this->codigo = $codigo;
        $this->bulto = $bulto;
    }


    public function verificarFactura()
    {
        $registro = array();
        $sql = "SELECT codigo_producto, nombre_producto, precio_unidades, unidades_caja
         FROM productos_tienda WHERE codigo_producto=$this->codigo";
        $result = $this->db->prepare($sql);
        $result->execute();
        $registro[] = $result->fetch(PDO::FETCH_ASSOC);
        $count = $result->rowCount();

        /*En esta condicion verifico si el producto existe en el catalogo de la tienda */
        /* Si la condicion es 1 entro*/
        if ($count === 1) {

            $sql = "SELECT codigo_producto FROM productos_factura WHERE codigo_producto =$this->codigo";
            $result = $this->db->prepare($sql);
            $result->execute();
            $count = $result->rowCount();

            /*En esta condicion verifico si el producto existe en la factura */
            /* Si la condicion es false entro e inserto un nuevo producto*/
            if ($count === 0) {

                $sql = "INSERT INTO productos_factura(codigo_producto, nombre_producto, unidades_caja, precio_unidades)
            VALUES(:cod, :nombre, :und, :precio)";
                $insert = $this->db->prepare($sql);

                foreach ($registro as $producto) {

                    $insert->bindParam(":cod", $producto['codigo_producto'], PDO::PARAM_INT);
                    $insert->bindParam(":nombre", $producto['nombre_producto'], PDO::PARAM_STR);
                    $insert->bindParam(":und", $producto['unidades_caja'], PDO::PARAM_INT);
                    $insert->bindParam(":precio", $producto['precio_unidades'], PDO::PARAM_STR);
                    $insert->execute();
                }
                $fecha = date("Y-m-d");

                $sql = "UPDATE productos_factura SET fecha_entrada= '$fecha', caja_facturada='0',
                caja_recibida=$this->bulto,producto_recibido='new'  WHERE codigo_producto=$this->codigo";
                $update = $this->db->prepare($sql);
                $update->execute();


                $sql = "INSERT INTO productos_comprobados(codigo_producto_tienda, codigo_producto_factura,
                nombre_producto)
            VALUES(:codt,:codf, :nombre)";
                $insert = $this->db->prepare($sql);

                foreach ($registro as $producto) {

                    $insert->bindParam(":codt", $producto['codigo_producto'], PDO::PARAM_INT);
                    $insert->bindParam(":codf", $producto['codigo_producto'], PDO::PARAM_INT);
                    $insert->bindParam(":nombre", $producto['nombre_producto'], PDO::PARAM_STR);
                    $insert->execute();
                }
                $sql = "UPDATE productos_comprobados SET caja_recibida=$this->bulto WHERE codigo_producto_tienda=$this->codigo";
                $update = $this->db->prepare($sql);
                $update->execute();
            } else {

                $sql = "SELECT codigo_producto_factura FROM productos_comprobados WHERE codigo_producto_factura=$this->codigo";
                $result = $this->db->prepare($sql);
                $result->execute();
                $count = $result->rowCount();

                /* En esta condicion verifico si el producto fue introcido anteriormente*/
                /* si es 0 entro en la condicion e inserto el valor en la base de tados*/
                if ($count === 0) {
                    try {
                        $sql = "INSERT INTO productos_comprobados(codigo_producto_factura, codigo_producto_tienda, nombre_producto)
                VALUES(:codt, :codf, :nombre)";
                        $insert = $this->db->prepare($sql);

                        foreach ($registro as $producto) {
                            $insert->bindParam(":codt", $producto['codigo_producto'], PDO::PARAM_INT);
                            $insert->bindParam(":codf", $producto['codigo_producto'], PDO::PARAM_INT);
                            $insert->bindParam(":nombre", $producto['nombre_producto'], PDO::PARAM_STR);
                            $insert->execute();
                        }
                        $sql = "UPDATE productos_factura SET caja_recibida=$this->bulto, producto_recibido='ok'
                        WHERE codigo_producto=$this->codigo";
                        $update = $this->db->prepare($sql);
                        $update->execute();
                        $sql = "UPDATE productos_comprobados SET caja_recibida=$this->bulto
                        WHERE codigo_producto_factura=$this->codigo";
                        $update = $this->db->prepare($sql);
                        $update->execute();
                    } catch (Exception $e) {
                        echo 'error en la linea' . $e->getLine() . " " . $e->getMessage();
                    }
                } else {
                    /* si no es 0 no entro en la condicion, de esa manera controlo que no se dupliquen los datos introducidos*/
                    $sql = "SELECT caja_recibida FROM productos_comprobados WHERE codigo_producto_factura= $this->codigo";
                    $resultado = $this->db->prepare($sql);
                    $resultado->execute();
                    $registro = $resultado->fetch(PDO::FETCH_ASSOC);
                    $this->bulto += $registro['caja_recibida'];
                    $sql = "UPDATE productos_comprobados SET caja_recibida = $this->bulto WHERE codigo_producto_factura=$this->codigo";
                    $update = $this->db->prepare($sql);
                    $update->execute();
                    $sql = "UPDATE productos_factura SET caja_recibida = $this->bulto WHERE codigo_producto=$this->codigo";
                    $update = $this->db->prepare($sql);
                    $update->execute();
                }
            }
        } else {
            $error = 0;
            return json_encode($error);
        }
    }

    public function mostrarTablaVerificar()
    {
        $sql = "SELECT codigo_producto FROM productos_tienda WHERE codigo_producto = $this->codigo";
        $resultado = $this->db->prepare($sql);
        $resultado->execute();
        $count = $resultado->rowCount();

        if ($count == 1) {
            $sql = "SELECT codigo_producto_factura, nombre_producto, caja_recibida FROM productos_comprobados";
            $resultado = $this->db->prepare($sql);
            $resultado->execute();
            $datos = array();
            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {

                $datos[] = [
                    'codigo_producto_factura' => $registro['codigo_producto_factura'],
                    'nombre_producto' => $registro['nombre_producto'],
                    'caja_recibida' => $registro['caja_recibida'],
                ];
            }
            echo json_encode($datos);
        } else {
            echo json_encode(0);
        }
    }


    public function eliminar($codigo)
    {

        $sql = "SELECT codigo_producto FROM productos_factura WHERE caja_facturada = 0  AND codigo_producto= $codigo";
        $resultado = $this->db->prepare($sql);
        $resultado->execute();
        $count = $resultado->rowCount();

        if ($count == 1) {
            $sql = "DELETE  FROM productos_comprobados 
            WHERE codigo_producto_factura = $codigo";
            $delete = $this->db->prepare($sql);
            $delete->execute();
            $sql = "DELETE  FROM productos_factura WHERE caja_facturada = 0 AND codigo_producto = $codigo";
            $delete = $this->db->prepare($sql);
            $delete->execute();
        }
        if ($count == 0) {
            $sql = "UPDATE productos_factura SET caja_recibida = 0, producto_recibido='not' WHERE codigo_producto = $codigo";
            $update = $this->db->prepare($sql);
            $update->execute();
        }
        $sql = "DELETE  FROM productos_comprobados 
            WHERE codigo_producto_factura = $codigo";
        $delete = $this->db->prepare($sql);
        $delete->execute();
    }

    public function cargarProductosTienda()
    {

        $sql = "SELECT codigo_producto, nombre_producto, unidades_caja, caja_facturada, precio_unidades,fecha_entrada,
         caja_recibida, producto_recibido 
        FROM productos_factura";
        $resultado = $this->db->prepare($sql);
        $resultado->execute();
        $productos = array();
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {

            $productos[] = [
                'codigo_producto' => $registro['codigo_producto'],
                'nombre_producto' => $registro['nombre_producto'],
                'unidades_caja' => $registro['unidades_caja'],
                'caja_facturada' => $registro['caja_facturada'],
                'precio_unidades' => $registro['precio_unidades'],
                'fecha_entrada' => $registro['fecha_entrada'],
                'caja_recibida' => $registro['caja_recibida'],
                'producto_recibido' => $registro['producto_recibido']
            ];
        }

        $sql = "INSERT INTO historial(codigo_producto, nombre_producto, unidades_caja, caja_facturada, 
        precio_unidades, fecha_entrada, caja_recibida, producto_recibido)
         VALUES(:cod, :nom, :unid, :caja, :precio, :fecha, :rec, :recb)";
        $insert = $this->db->prepare($sql);

        //var_dump($productos);
        foreach ($productos as $producto) {

            $insert->bindParam(":cod", $producto['codigo_producto'], PDO::PARAM_INT);
            $insert->bindParam(":nom", $producto['nombre_producto'], PDO::PARAM_STR);
            $insert->bindParam(":unid", $producto['unidades_caja'], PDO::PARAM_INT);
            $insert->bindParam(":caja", $producto['caja_facturada'], PDO::PARAM_INT);
            $insert->bindParam(":precio", $producto['precio_unidades'], PDO::PARAM_STR);
            $insert->bindParam(":fecha", $producto['fecha_entrada'], PDO::PARAM_STR);
            $insert->bindParam(":rec", $producto['caja_recibida'], PDO::PARAM_INT);
            $insert->bindParam(":recb", $producto['producto_recibido'], PDO::PARAM_STR);
            $insert->execute();
        }

        /*Modificamos el campo caja recibida cual valor es 0 y producto recibido cual valor es not en la tabla historial*/
        /* $sql = "UPDATE historial h JOIN productos_comprobados c ON h.codigo_producto = c.codigo_producto_historial 
        SET h.caja_recibida = c.caja_recibida, h.producto_recibido='ok'";
        $update = $this->db->prepare($sql);
        $update->execute();*/
        /*Modificamos el campo unidades tienda */
        $sql = "UPDATE productos_factura f
        JOIN productos_comprobados c ON f.codigo_producto = c.codigo_producto_factura
        JOIN productos_tienda t ON c.codigo_producto_tienda = t.codigo_producto
        SET t.unidades_tienda = c.caja_recibida * t.unidades_caja + t.unidades_tienda";
        $update = $this->db->prepare($sql);
        $update->execute();
        /*Eliminamos todos los registros de la tabla productos_comprobados*/
        $sql = "DELETE  FROM productos_comprobados";
        $delete = $this->db->prepare($sql);
        $delete->execute();
        $sql = "DELETE  FROM productos_factura";
        $delete = $this->db->prepare($sql);
        $delete->execute();

        echo 'ok';
    }

    public function mostrarFaltas($fecha)
    {
        $sql = "SELECT fecha_entrada FROM historial WHERE fecha_entrada = '$fecha'";
        $resultado  = $this->db->prepare($sql);
        $resultado->execute();
        $count = $resultado->rowCount();
        

        if ($count >= 1) {
            $sql = "SELECT codigo_producto, nombre_producto, caja_facturada, caja_recibida, fecha_entrada, precio_unidades
            FROM historial WHERE caja_recibida < caja_facturada AND fecha_entrada = '$fecha'";
            $resultado = $this->db->prepare($sql);
            $resultado->execute();
            $datos = array();
            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {

                $datos[] = [
                    'codigo_producto' => $registro['codigo_producto'],
                    'nombre_producto' => $registro['nombre_producto'],
                    'precio_unidades' => $registro['precio_unidades'],
                    'caja_recibida' => $registro['caja_recibida'],
                    'caja_facturada' => $registro['caja_facturada'],
                    'fecha_entrada' => $registro['fecha_entrada']
                ];
            }
            echo json_encode($datos);
        } else {
            echo json_encode(0);
        }
    }
    public function mostrarDevolucion($fecha)
    {
        $sql = "SELECT fecha_entrada FROM historial WHERE fecha_entrada = '$fecha'";
        $resultado  = $this->db->prepare($sql);
        $resultado->execute();
        $count = $resultado->rowCount();

        if ($count >= 1) {
            $sql = "SELECT codigo_producto, nombre_producto, caja_facturada, caja_recibida, fecha_entrada, precio_unidades
        FROM historial WHERE producto_recibido='new' AND fecha_entrada = '$fecha'";
            $resultado = $this->db->prepare($sql);
            $resultado->execute();
            $datos = array();
            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $datos[] = [
                    'codigo_producto' => $registro['codigo_producto'],
                    'nombre_producto' => $registro['nombre_producto'],
                    'precio_unidades' => $registro['precio_unidades'],
                    'caja_recibida' => $registro['caja_recibida'],
                    'caja_facturada' => $registro['caja_facturada'],
                    'fecha_entrada' => $registro['fecha_entrada']
                ];

                echo json_encode($datos);
            }
        } else {
            echo json_encode(0);
        }
    }

    public function consultarProducto()
    {
        $sql = "SELECT codigo_producto FROM productos_tienda WHERE codigo_producto= $this->codigo";
        $result = $this->db->prepare($sql);
        $result->execute();
        $count = $result->rowCount();

        if ($count === 1) {
            $sql = "SELECT codigo_producto, nombre_producto, unidades_tienda, precio_unidades, unidades_caja
            FROM productos_tienda WHERE codigo_producto ='$this->codigo'";
            $result = $this->db->prepare($sql);
            $result->execute();
            $datos = array();

            while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {

                $datos[] = [
                    'codigo_producto' => $registro['codigo_producto'],
                    'nombre_producto' => $registro['nombre_producto'],
                    'precio_unidades' => $registro['precio_unidades'],
                    'unidades_caja' => $registro['unidades_caja'],
                    'unidades_tienda' => $registro['unidades_tienda']
                ];
            }
            echo json_encode($datos);
        } else {
            echo json_encode(0);
        }
    }
    public function modificarProducto()
    {
        $sql = "SELECT codigo_producto FROM productos_tienda WHERE codigo_producto= $this->codigo";
        $result = $this->db->prepare($sql);
        $result->execute();
        $count = $result->rowCount();


        if ($count === 1) {

            $sql = "SELECT codigo_producto, nombre_producto, unidades_tienda
         FROM productos_tienda WHERE codigo_producto ='$this->codigo'";
            $result = $this->db->prepare($sql);
            $result->execute();
            $datos = array();
            while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {

                $datos[] = [
                    'codigo_producto' => $registro['codigo_producto'],
                    'nombre_producto' => $registro['nombre_producto'],
                    'unidades_tienda' => $registro['unidades_tienda'],
                ];
            }
            echo json_encode($datos);
        } else {
            echo json_encode(0);
        }
    }

    public function mdProducto($unidades)
    {
        $sql = "UPDATE productos_tienda 
            SET  unidades_tienda = $unidades
            WHERE codigo_producto= $this->codigo";
        $result = $this->db->prepare($sql);
        $result->execute();

        echo 'ok';
    }
}
