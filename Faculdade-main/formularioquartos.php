<?php 
if (isset($_POST['submit'])) {
    include_once('config.php');

    // Captura os dados do formulário
    $nome = $_POST['nome']; 
    $email = $_POST['email']; 
    $quarto = $_POST['quarto']; 
    $data_entrada = $_POST['data_entrada'];
    $data_saida = $_POST['data_saida'];

    // Cálculo do preço baseado no quarto e nas datas
    $preco_por_noite = 0;

    // Preços dos quartos por noite
    switch ($quarto) {
        case 'luxo_2_camas':
            $preco_por_noite = 200;
            break;
        case 'basico_1_cama':
            $preco_por_noite = 100;
            break;
        case 'luxo_3_camas':
            $preco_por_noite = 250;
            break;
        case 'basico_2_camas':
            $preco_por_noite = 150;
            break;
        case 'luxo_1_cama':
            $preco_por_noite = 180;
            break;
    }

    // Calculando o número de dias entre as datas de entrada e saída
    $data_entrada_timestamp = strtotime($data_entrada);
    $data_saida_timestamp = strtotime($data_saida);
    $dias_estadia = ($data_saida_timestamp - $data_entrada_timestamp) / (60 * 60 * 24); // converte segundos para dias

    // Verifica se as datas são válidas e o número de dias é positivo
    if ($dias_estadia > 0) {
        $total_preco = $preco_por_noite * $dias_estadia;
    } else {
        $total_preco = 0;
        echo "Erro: a data de saída deve ser posterior à data de entrada.";
    }

    // Insere os dados no banco
    $result = mysqli_query($conexao, "INSERT INTO quartos (quarto, nome, email, data_entrada, data_saida, total_preco) 
                                      VALUES ('$quarto', '$nome', '$email', '$data_entrada', '$data_saida', '$total_preco')");

    if ($result) {
        header('Location: quartos.php');
        exit();
    } else {
        echo "Erro ao cadastrar os dados: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário | Clientes</title>

    <link rel="stylesheet" href="FORMULARIOS.css">
</head>
<body>
    <a href="funcionarios.php" style="color: white; text-decoration: none;">Voltar</a>
    <div class="box">
        <form action="formularioquartos.php" method="POST">
            <fieldset>
                <legend><b>Formulário de Quartos</b></legend>
                <br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome" class="labelInput">Nome Completo</label>
                </div>

                <div class="inputBox">
                    <input type="email" name="email" id="email" class="inputUser" required>
                    <label for="email" class="labelInput">E-mail</label>
                </div>

                <br>
                <label for="quarto"><b>Selecione o Quarto:</b></label>
                <select name="quarto" id="quarto" required>
                    <option value="" disabled selected>Escolha uma opção</option>
                    <option value="luxo_2_camas">Quarto de Luxo - 2 Camas - Com Sacada</option>
                    <option value="basico_1_cama">Quarto Básico - 1 Cama - Sem Sacada</option>
                    <option value="luxo_3_camas">Quarto de Luxo - 3 Camas - Com Sacada</option>
                    <option value="basico_2_camas">Quarto Básico - 2 Camas - Sem Sacada</option>
                    <option value="luxo_1_cama">Quarto de Luxo - 1 Cama - Sem Sacada</option>
                </select>

                <br><br>
                <div class="inputBox">
                    <input type="date" name="data_entrada" id="data_entrada" class="inputUser" required>
                    <label for="data_entrada" class="labelInput">Data de Entrada</label>
                </div>

                <div class="inputBox">
                    <input type="date" name="data_saida" id="data_saida" class="inputUser" required>
                    <label for="data_saida" class="labelInput">Data de Saída</label>
                </div>

                <br>
                <div class="inputBox">
                    <label for="preco">Preço Total: R$</label>
                    <input type="text" name="preco" id="preco" class="inputUser" readonly value="<?php echo isset($total_preco) ? $total_preco : ''; ?>" />
                </div>

                <input type="submit" name="submit" id="submit" value="Cadastrar">
            </fieldset>
        </form>
    </div>
</body>
</html>
