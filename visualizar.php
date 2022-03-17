<?php 
// MOSTRAR A MENSAGEM DE SUCESSO DO CADASTRO, COLOCADA EM UMA VARIAVEL GLOBAL
session_start();

// FUNCAO ob_start(); Para nao dar erro de direcionamento depois de cadastrar o usuario
// LIMPA o Buffer de saida
ob_start();

// INCLUI o banco de dados que se encontrar no arquivo conexao.php
include_once './conexao.php';

// RECEBENDO O ID - POIS O VISUALIZAR E BUSCADO PELO ID
// E um tipo input GET, que tras o valor armazenado em id (setado no outro script READ) e filtro dizendo que deve ser um INTEIRO.
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// VERIFICO SE REALMENTE ESTOU RECEBENDO O ID
if(empty($id)) {
    // VERIFICA - SE ESTIVER VAZIO O RETORNO DO ID CONSTA ERRO
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header('Location: index.php');
    // EXIT PARA O FUNCIONAMENTO
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar</title>
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="cadastrar.php">Cadastrar</a><br>
    <h1>Visualizar</h1>

    
    <?php
    // CASO O ID VOLTE NORMALMENTE, BUSCAMOS NO BANCO DE DADOS
    $query_usuario = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->execute();

    // VERIFICO O RESULTADO
    if(($result_usuario) AND ($result_usuario->rowCount() != 0)) {
        // SE O RESULTADO FOR DIFERENTE DE 0, SIGNIFICA QUE EXISTE UM REGISTRO
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        
        // var_dump($row_usuario);

        // FORMA SEM O EXTRACT
        # echo "ID: " . $row_usuario['id'] . "<br>";

        extract($row_usuario);
        echo "ID: $id <br>";
        echo "Nome: $nome <br>";
        echo "Email: $email <br>";
        
    } else {
        // CASO TENHA ERRO REDIRECIONO NOVAMENTE O USUARIO
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
        header('Location: index.php');
    }
    ?>
</body>
</html>