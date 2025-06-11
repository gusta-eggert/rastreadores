<?php
// buscar_data_saida.php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'estoque_rastreadores';
$conn = new mysqli($host, $user, $pass, $db);

$imei = $_GET['imei'] ?? '';

$data_saida = null;

if ($imei) {
    $stmt = $conn->prepare("SELECT data_saida FROM movimentacoes WHERE imei = ? AND data_volta IS NULL ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $imei);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $data_saida = $row['data_saida'];
    }
}

header('Content-Type: application/json');
echo json_encode(['data_saida' => $data_saida]);
?>
