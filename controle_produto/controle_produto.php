<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Controle de Produtos</title>
</head>
<body>
    
    <div class="container">
        <h1 id="titulo-form">Cadastro de Produtos</h1>
        <div class="form-cadastro">
            <form action="criar_db.php" method="post">

                
                <label for="descricao">Descrição:</label><br>    
                <input type="text" id="descricao" name="descricao" placeholder="Digite a descrição do produto" required><br><br>
                
                <label for="categoria">Categoria:</label><br>
                <select id="categoria" name="categoria" required>
                    <option value="informatica">Informática</option>
                    <option value="eletronicos">Eletrônicos</option>
                    <option value="material">Material</option>
                </select><br><br>
                      
                <label for="valor_compra">Valor Compra(R$):</label><br>
                <input type="number" id="valor_compra" name="valor_compra" required><br><br>

                <label for="valor_venda">Valor Venda(R$):</label><br>
                <input type="number" id="valor_venda" name="valor_venda" required><br><br>

                <label for="quantidade">Qtd. Estoque:</label><br>
                <input type="number" id="estoque" name="estoque" required><br><br>                    
                
                <input id="btn-cadastrar" type="submit" value="Cadastrar Produto">
                <input id="btn-vender" type="submit" value="Vender Produto">
            </form>
        </div>

        <form class="barra-pesquisa" action="controle_produto.php" method="get">
            <input type="text" name="pesquisa" value="<?php echo isset($_GET['pesquisa']) ? htmlspecialchars($_GET['pesquisa'], ENT_QUOTES) : ''; ?>" placeholder="Pesquisar produtos...">
            <button type="submit" id="btn-pesquisar">Pesquisar</button>
        </form>

        <div class="lista-produtos">
            <h1>Inventário</h1>
            <table>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Valor Compra(R$)</th>
                    <th>Valor Venda(R$)</th>
                    <th>Qtd. Estoque</th>
                    <th>Ações</th>
                </tr>

                <?php
                include 'db_conexao.php';
                $pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

                if ($pesquisa !== '') {
                    $termo = '%' . $pesquisa . '%';
                    $sql = "SELECT id, descricao, categoria, valor_compra, valor_venda, estoque FROM produtos WHERE descricao LIKE ? OR categoria LIKE ? OR CAST(valor_compra AS CHAR) LIKE ? OR CAST(valor_venda AS CHAR) LIKE ? OR CAST(estoque AS CHAR) LIKE ?";

                    if ($stmt = $mysqli->prepare($sql)) {
                        $stmt->bind_param('sssss', $termo, $termo, $termo, $termo, $termo);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = $mysqli->query("SELECT id, descricao, categoria, valor_compra, valor_venda, estoque FROM produtos");
                    }
                } else {
                    $result = $mysqli->query("SELECT id, descricao, categoria, valor_compra, valor_venda, estoque FROM produtos");
                }

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["descricao"]) . "</td><td>" . htmlspecialchars($row["categoria"]) . "</td><td>" . htmlspecialchars($row["valor_compra"]) . "</td><td>" . htmlspecialchars($row["valor_venda"]) . "</td><td>" . htmlspecialchars($row["estoque"]) . "</td><td><a href='deletar_produto.php?id=" . $row["id"] . "' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\")' class='btn-delete'>Excluir</a></td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum produto cadastrado</td></tr>";
                }
                $mysqli->close();
                ?>

            </table>
        </div>
    </div>
</body>
</html>