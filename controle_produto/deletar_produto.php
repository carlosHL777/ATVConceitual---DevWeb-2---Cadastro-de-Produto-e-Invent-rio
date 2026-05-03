<?php
include 'db_conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Produto excluído com sucesso!'); window.location.href='controle_produto.php';</script>";
        } else {
            echo "<script>alert('Produto não encontrado.'); window.location.href='controle_produto.php';</script>";
        }
    } else {
        echo "<script>alert('Erro ao excluir produto: " . $stmt->error . "'); window.location.href='controle_produto.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ID inválido.'); window.location.href='controle_produto.php';</script>";
}

$mysqli->close();
?>