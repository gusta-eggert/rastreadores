<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imei = $_POST['imei'] ?? '';
    $data_volta = $_POST['data_volta'] ?? '';
    $motivo = $_POST['motivo'] ?? '';

    // Buscar a data de saída da movimentação mais recente
    $stmt = $conn->prepare("SELECT data_saida FROM movimentacoes WHERE imei = ? AND data_volta IS NULL ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $imei);
    $stmt->execute();
    $stmt->bind_result($data_saida);
    $stmt->fetch();
    $stmt->close();

    // Validar se a data de volta é anterior à de saída
    if ($data_volta < $data_saida) {
        echo "<script>alert('Erro: A data de volta não pode ser anterior à data de saída!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Atualiza movimentação
    $stmt = $conn->prepare("UPDATE movimentacoes SET data_volta = ?, motivo = ? WHERE imei = ? AND data_volta IS NULL ORDER BY id DESC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("sss", $data_volta, $motivo, $imei);
        $stmt->execute();
        $stmt->close();
    }

    // Atualiza status
    $stmt2 = $conn->prepare("UPDATE rastreadores SET situacao = 'Disponível' WHERE imei = ?");
    if ($stmt2) {
        $stmt2->bind_param("s", $imei);
        $stmt2->execute();
        $stmt2->close();
    }

    header('Location: index.php');
    exit;
}
?>
