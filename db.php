<?php

$host    = "127.0.0.1";
$usuario = "root";
$senha   = "";
$banco   = "tcc1";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
