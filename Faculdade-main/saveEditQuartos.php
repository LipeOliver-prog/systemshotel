<?php
if (isset($_POST['update'])) {
    include_once('config.php');

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $quarto = $_POST['quarto'];
    $data_entrada = $_POST['data_entrada'];
    $data_saida = $_POST['data_saida'];

    // Calcula o preço atualizado
    $preco_por_noite = 0;
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

    $dias_estadia = (strtotime($data_saida) - strtotime($data_entrada)) / (60 * 60 * 24);
    $total_preco = ($dias_estadia > 0) ? $preco_por_noite * $dias_estadia : 0;

    $sqlUpdate = "UPDATE quartos SET nome='$nome', email='$email', quarto='$quarto', data_entrada='$data_entrada', data_saida='$data_saida', total_preco='$total_preco' WHERE idquartos=$id";

    if ($conexao->query($sqlUpdate) === TRUE) {
        header('Location: quartos.php');
    } else {
        echo "Erro ao atualizar os dados: " . $conexao->error;
    }
}
?>
