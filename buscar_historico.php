<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'estoque_rastreadores';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha na conexão com o banco']);
    exit;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$imei = $_GET['imei'] ?? '';
$historico = [];

if ($imei) {
    $stmt = $conn->prepare("SELECT cliente, data_saida, data_volta FROM movimentacoes WHERE imei = ? ORDER BY data_saida DESC");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro na query']);
        exit;
    }
    $stmt->bind_param("s", $imei);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $historico[] = $row;
    }
}

echo json_encode($historico);
