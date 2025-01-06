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
    $sql = "SELECT * FROM funcionarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM funcionarios ORDER BY id DESC";
}
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Funcionários</title>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <link rel="stylesheet" href="FUNCIONARIOS.css">

</head>

<body>




    <header class="header">
        <a href="Home.php" class="logo">
            <img src="img/logoSite.png" alt="logo">
        </a>

        <nav class="navbar">
            <a href="Home.php">Home</a>
            <a href="Funcionarios.php" class="active">Funcionarios</a>
            <a href="Quartos.php">Quartos</a>
            <a href="Clientes.php">Clientes</a>
            <a href="frigobar.php">Frigobar</a>
            <a href="pagamento.php">Pagamento</a>
           
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



    <div class="box-search1">
        <div class="box">
            <a href="formulario.php">Cadastro</a>
        </div>

        <div class="search1">

            <input type="search" class="form-control search-field" placeholder="Pesquisar" id="pesquisar">

            <button onclick="searchData()" class="btn3 btn-primary">

                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
                    <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0' />
                </svg>
        </div>
        </button>


        <div class="table">
            <table class="table-item">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">sexo</th>
                        <th scope="col">Data de Nascimento</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">...</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    while ($user_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $user_data['id'] . "</td>";
                        echo "<td>" . $user_data['nome'] . "</td>";
                        echo "<td>" . $user_data['email'] . "</td>";
                        echo "<td>" . $user_data['senha'] . "</td>";
                        echo "<td>" . $user_data['telefone'] . "</td>";
                        echo "<td>" . $user_data['sexo'] . "</td>";
                        echo "<td>" . $user_data['data_nascimento'] . "</td>";
                        echo "<td>" . $user_data['cidade'] . "</td>";
                        echo "<td>" . $user_data['estado'] . "</td>";
                        echo "<td>" . $user_data['endereco'] . "</td>";
                        echo "<td>
                            <a class='btn1 btn-sm btn-primary' href='edit.php?id=$user_data[id]'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                            <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                            <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
                            </svg> 
                            </a>    
                            <a class='btn2 btn-sm btn-danger' href='delete.php?id=$user_data[id]'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                            <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                            </svg>
                            </a>               
                    </td>";
                        echo "<td>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

</body>
<script>
    var search = document.getElementById('pesquisar');

    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") { // Verifica se a tecla pressionada é o Enter
            searchData();
        }
    });

    function searchData() {
        window.location = 'funcionarios.php?search=' + search.value;
    }
</script>


</html>