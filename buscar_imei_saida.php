<?php
// buscar_imei_saida.php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'estoque_rastreadores';
$conn = new mysqli($host, $user, $pass, $db);

$term = $_GET['term'] ?? '';

$result = [];

if ($term) {
    $stmt = $conn->prepare("SELECT patrimonio, imei FROM rastreadores WHERE situacao = 'Disponível' AND imei LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param('s', $term);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $result[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($result);
