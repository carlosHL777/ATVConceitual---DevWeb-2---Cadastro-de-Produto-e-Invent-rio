<?php
$servidor="127.0.0.1";
$usuario="root";
$senha="usbw";
$banco="produtos";
$mysqli = new mysqli($servidor, $usuario, $senha, $banco);
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

?>