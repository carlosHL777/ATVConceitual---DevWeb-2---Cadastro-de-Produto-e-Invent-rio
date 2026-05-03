<?php

    include 'db_conexao.php';

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'cadastrar') {
            $descricao = $_POST["descricao"];
            $categoria = $_POST["categoria"];
            $valor_compra = $_POST["valor_compra"];
            $valor_venda = $_POST["valor_venda"];
            $estoque = $_POST["estoque"];

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
        } elseif ($action == 'vender') {
            $descricao = trim($_POST["descricao"]);

            if (empty($descricao)) {
                echo "<script>alert('Por favor, insira a descrição do produto para vender.'); window.location.href='controle_produto.php';</script>";
                exit;
            }

            $sql = "SELECT id, estoque FROM produtos WHERE descricao = ? LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $descricao);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $estoque_atual = $row['estoque'];

                if ($estoque_atual > 0) {
                    $novo_estoque = $estoque_atual - 1;
                    $update_sql = "UPDATE produtos SET estoque = ? WHERE id = ?";
                    $update_stmt = $mysqli->prepare($update_sql);
                    $update_stmt->bind_param("ii", $novo_estoque, $id);

                    if ($update_stmt->execute()) {
                        echo "<script>alert('Produto vendido com sucesso! Estoque atualizado.'); window.location.href='controle_produto.php';</script>";
                    } else {
                        echo "<script>alert('Erro ao atualizar estoque: " . addslashes($update_stmt->error) . "'); window.location.href='controle_produto.php';</script>";
                    }

                    $update_stmt->close();
                } else {
                    echo "<script>alert('Estoque insuficiente para vender este produto.'); window.location.href='controle_produto.php';</script>";
                }
            } else {
                echo "<script>alert('Produto não encontrado com a descrição fornecida.'); window.location.href='controle_produto.php';</script>";
            }

            $stmt->close();
        }
    }

    $mysqli->close();

?>