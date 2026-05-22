<?php
include __DIR__ . "/../db.php";

$sql = "INSERT INTO statusloja (id, aberto, cardapio_do_dia) VALUES (1, 0, '')";
$conn->query($sql);

echo "inserido";
