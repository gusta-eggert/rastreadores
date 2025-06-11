<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'estoque_rastreadores';
$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_modelo']); // Corrigido
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO modelos (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        $stmt->execute();
    }
    // Redireciona de volta ao index
    header("Location: index.php");
    exit;
}
