<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imei = $_POST['imei'];
    $cliente = $_POST['cliente'];
    $data_saida = $_POST['data_saida'];
    $motivo = $_POST['motivo'];

    // Insere a movimentação
    $sql_mov = "INSERT INTO movimentacoes (imei, cliente, data_saida, motivo)
                VALUES (?, ?, ?, ?)";

    $stmt_mov = $conn->prepare($sql_mov);
    $stmt_mov->bind_param("ssss", $imei, $cliente, $data_saida, $motivo);
    $stmt_mov->execute();

    // Atualiza a situação do rastreador para "Em Cliente"
    $sql_update = "UPDATE rastreadores SET situacao = 'Em Cliente' WHERE imei = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $imei);
    $stmt_update->execute();

    $stmt_mov->close();
    $stmt_update->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>