<?php

require_once('factura.php');
require_once('producto.php');
$operacion = $_REQUEST['operacion'];

$factura = new Factura();
$codigo = (isset($_REQUEST['codigo'])) ? $_REQUEST['codigo'] : 0;

$bulto = (isset($_REQUEST['bulto'])) ? $_REQUEST['bulto'] : 0;
$producto = new Producto($codigo, $bulto);

switch ($operacion) {
    case 'cargar':
        $factura->cargarFactura();
        $factura->insertarFactura();
    case 'mostrar':
        $factura->mostrarProductosFactura();
        break;
    case 'verificar':
        $producto->verificarFactura();
        $producto->mostrarTablaVerificar();
        break;
    case 'filtrar':
        $producto->cargarProductosTienda();
        break;
    case 'faltas':
        $fecha = $_GET['fecha'];
        $producto->mostrarFaltas($fecha);
        break;
    case 'devolucion':
        $fecha = $_GET['fecha'];
        $producto->mostrarDevolucion($fecha);
        break;
    case 'eliminar':
        $producto->eliminar($codigo);
        $producto->mostrarTablaVerificar();
        break;
    case 'consultar':
        $producto->consultarProducto();
        break;
    case 'modificar':
        $producto->modificarProducto();
        break;
    case 'mod':
        $unidades = $_GET['unidades'];
        $producto->mdProducto($unidades);

        break;
    default:
        break;
}
