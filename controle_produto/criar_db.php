<?php

    include 'db_conexao.php';
    $descricao = $_POST ["descricao"];
    $categoria = $_POST ["categoria"];
    $valor_compra = $_POST ["valor_compra"];
    $valor_venda = $_POST ["valor_venda"];
    $estoque = $_POST ["estoque"];

    
    $createTable = "CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        categoria VARCHAR(50) NOT NULL,
        valor_compra DECIMAL(10,2) NOT NULL,
        valor_venda DECIMAL(10,2) NOT NULL,
        estoque INT NOT NULL
    )";

    if ($mysqli->query($createTable) === TRUE) {
        
    } else {
        echo "<script>alert('Erro ao criar tabela: " . addslashes($mysqli->error) . "'); window.location.href='controle_produto.php';</script>";
        exit;
    }

    $sql = "INSERT INTO produtos (descricao, categoria, valor_compra, valor_venda, estoque) VALUES (?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssddi", $descricao, $categoria, $valor_compra, $valor_venda, $estoque);

    if ($stmt->execute()) {
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='controle_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar produto: " . addslashes($stmt->error) . "'); window.location.href='controle_produto.php';</script>";
    }

    $stmt->close();
    $mysqli->close();

?>