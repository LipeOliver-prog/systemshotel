<?php

session_start();
include_once('config.php');
// print_r($_SESSION);
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['email'];
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM clientes WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM clientes ORDER BY id DESC";
}
$result = $conexao->query($sql);

include_once('config.php');

if (isset($_POST['buscar_cliente'])) {
    $id_busca = $_POST['id_busca'];
    $query = "SELECT * FROM clientes WHERE id = '$id_busca'";
    $result = $conexao->query($query);

    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        $erro = "Cliente não encontrado!";
    }
}

if (isset($_POST['gerar_boleto'])) {
    $nome_cliente = $_POST['nome_cliente'];
    $cpf_cliente = $_POST['cpf_cliente'];
    $vencimento = $_POST['vencimento'];
    $valor_boleto = floatval($_POST['valor']); // Valor do boleto
    $valor_frigobar = floatval($_POST['valor_frigobar']); // Valor do frigobar

    // Somar os valores
    $valor_total = $valor_boleto + $valor_frigobar;

    $nosso_numero = $_POST['nosso_numero'];
    $codigo_banco = $_POST['codigo_banco'];

    // Inserir dados na tabela boletos
    $query_inserir = "INSERT INTO boletos (nome_cliente, cpf_cliente, vencimento, valor, nosso_numero, codigo_banco) 
                      VALUES ('$nome_cliente', '$cpf_cliente', '$vencimento', '$valor_total', '$nosso_numero', '$codigo_banco')";

    if ($conexao->query($query_inserir) === TRUE) {
        echo "Boleto gerado e inserido com sucesso na tabela!";
    } else {
        echo "Erro ao inserir o boleto: " . $conexao->error;
    }

    // Exibir os dados do boleto
    $valor_formatado = "R$ " . number_format($valor_total, 2, ',', '.');

    echo "<h2>Boleto Gerado</h2>";
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
    echo "<tr><th colspan='2'>Dados do Boleto</th></tr>";
    echo "<tr><td><strong>Nome do Cliente</strong></td><td>$nome_cliente</td></tr>";
    echo "<tr><td><strong>CPF do Cliente</strong></td><td>$cpf_cliente</td></tr>";
    echo "<tr><td><strong>Data de Vencimento</strong></td><td>$vencimento</td></tr>";
    echo "<tr><td><strong>Valor Frigobar</strong></td><td>$valor_frigobar</td></tr>";
    echo "<tr><td><strong>Valor Total</strong></td><td>$valor_formatado</td></tr>";
    echo "<tr><td><strong>Nosso Número</strong></td><td>$nosso_numero</td></tr>";
    echo "<tr><td><strong>Código do Banco</strong></td><td>$codigo_banco</td></tr>";
    echo "</table>";

    echo "<h3>Visualização do Boleto</h3>";
    echo "<div style='border: 1px solid #000; padding: 20px; width: 600px; margin-top: 20px;'>";
    echo "<h4>Banco: $codigo_banco</h4>";
    echo "<p><strong>Nosso Número: </strong>$nosso_numero</p>";
    echo "<p><strong>Cliente: </strong>$nome_cliente</p>";
    echo "<p><strong>CPF: </strong>$cpf_cliente</p>";
    echo "<p><strong>Vencimento: </strong>$vencimento</p>";
    echo "<p><strong>Valor Frigobar: </strong>  $valor_frigobar</p>";
    echo "<p><strong>Valor Total: </strong>$valor_formatado</p>";
    echo "<p><strong>Codigo de barras: </strong>[CODIGO DE BARRAS SIMULADO]</p>";
    echo "<p><strong>Instruções: </strong>Após o pagamento, aguarde confirmação.</p>";
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Boleto</title>

    <link rel="stylesheet" href="PAGAMENTOS.css">

</head>

<body>
    <header class="header">
        <a href="Home.php" class="logo">
            <img src="img/logoSite.png" alt="logo">
        </a>

        <nav class="navbar">
            <a href="Home.php">Home</a>
            <a href="Funcionarios.php">Funcionarios</a>
            <a href="Quartos.php">Quartos</a>
            <a href="Clientes.php">Clientes</a>
            <a href="frigobar.php">Frigobar</a>
            <a href="pagamento.php" class="active">Pagamento</a>
           
        </nav>

        <div class="bemVindo">
            <?php
            echo "<p>Bem vindo</p> <u>$logado</u>";
            ?>
        </div>

        <div class="buttonSair">
            <a href="sair.php" class="btnSair">Sair</a>
        </div>
    </header>

    <h1>Preencha os Dados para Gerar o Boleto</h1>

    <!-- Buscar cliente -->
    <form action="" method="POST">
        <label for="id_busca">Buscar Cliente pelo ID:</label>
        <input type="text" name="id_busca" id="id_busca" required>
        <button type="submit" name="buscar_cliente">Buscar</button>
    </form>

    <?php if (isset($erro)) {
        echo "<p style='color:red;'>$erro</p>";
    } ?>

    <!-- Formulário do boleto -->
    <form action="" method="POST">
        <label for="nome_cliente">Nome do Cliente:</label>
        <input type="text" name="nome_cliente" id="nome_cliente" value="<?= $cliente['nome'] ?? '' ?>" required><br><br>

        <label for="cpf_cliente">CPF do Cliente:</label>
        <input type="text" name="cpf_cliente" id="cpf_cliente" value="<?= $cliente['cpf'] ?? '' ?>" required><br><br>

        <label for="vencimento">Data de Vencimento:</label>
        <input type="date" name="vencimento" id="vencimento" required ><br><br>

        <label for="valor">Valor do Boleto:</label>
        <input type="text" name="valor" id="valor" required autocomplete="off"><br><br>

        <label for="valor_frigobar">Valor do Frigobar:</label>
        <input type="text" name="valor_frigobar" id="valor_frigobar" required autocomplete="off"><br><br>


        <label for="nosso_numero">Nosso Número:</label>
        <input type="text" name="nosso_numero" id="nosso_numero" required autocomplete="off"><br><br>

        <label for="codigo_banco">Código do Banco:</label>
        <input type="text" name="codigo_banco" id="codigo_banco" required autocomplete="off"><br><br>

        <button type="submit" name="gerar_boleto">Gerar Boleto</button>

        <div class="button-voltar">
            <a href="Home.php" class="btn-voltar">Voltar para Início</a>
        </div>


    </form>
</body>

</html>