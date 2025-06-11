<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imei = trim($_POST['imei'] ?? '');
    $cliente = trim($_POST['cliente'] ?? '');
    $data_saida = trim($_POST['data_saida'] ?? '');
    $motivo = trim($_POST['motivo'] ?? '');

    if (empty($imei) || empty($cliente) || empty($data_saida)) {
        exit('IMEI, Cliente e Data de Saída são obrigatórios.');
    }

    // Insere movimentação
    $sql_mov = "INSERT INTO movimentacoes (imei, cliente, data_saida, motivo) VALUES (?, ?, ?, ?)";
    $stmt_mov = $conn->prepare($sql_mov);
    $stmt_mov->bind_param("ssss", $imei, $cliente, $data_saida, $motivo);

    if (!$stmt_mov->execute()) {
        die('Erro ao inserir movimentação: ' . $stmt_mov->error);
    }

    // Atualiza rastreador para "Em Cliente"
    $sql_update = "UPDATE rastreadores SET situacao = 'Em Cliente' WHERE imei = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $imei);

    if (!$stmt_update->execute()) {
        die('Erro ao atualizar rastreador: ' . $stmt_update->error);
    }

    $stmt_mov->close();
    $stmt_update->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>
