<?php
include_once('usuario.php');

$usuario = $_POST['usuario'];
$pass = $_POST['pass'];

$usuario = new Usuario($usuario, $pass);
$usuario->login();

?>
