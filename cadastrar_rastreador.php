<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'estoque_rastreadores';
$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patrimonio = $_POST['patrimonio'];
    $imei = $_POST['imei'];
    $id_modelo = $_POST['id_modelo'];
    $situacao = 'Disponível';

    $stmt = $conn->prepare("INSERT INTO rastreadores (patrimonio, imei, id_modelo, situacao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $patrimonio, $imei, $id_modelo, $situacao);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
