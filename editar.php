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

// PESQUISAR O ID NO BANCO DE DADOS
$query_usuario = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

// VERIFICO SE ENCONTROU UM REGISTRO

if (($result_usuario) AND ($result_usuario->rowCount() != 0 )) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
    # var_dump($row_usuario);

} else {
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
    <title>Editar</title>

    <?php
    ### VERIFICAR OS DADOS COLOCADOS NO INPUT - RECEBER OS DADOS DO FORM ###
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    // FILTER DEFAULT - SIGNIFICA QUE QUERO RECEBER TUDO COMO STRING
    
    //VERIFICAR SE O USUARIO CLICOU NO BOTAO
    if(!empty($dados['EditUsuario'])) {
        #var_dump($dados);

        $empty_input = false;
        //QUANDO HOUVER UM ERRO A VARIAVEL SE TORNA TRUE
        
        // PRIMEIRO TIRAR TODOS OS ESPACOS EM BRANCO, TRIM E PARA O INICIO E O FINAL
        $dados = array_map('trim', $dados);
        
        // VERIFICO SE NO ARRAY, ALGUMA POSICAO ESTA VAZIO NO ARRAY, SIGNIFICA QUE O USUARIO NAO PREENCHEU
        if (in_array("",$dados)) {
            $empty_input = true;
            echo "<p style='color: #f00;'>Erro: Necessário preencher todos os campos!</p>";
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $empty_input = true;
            echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";    
        }

        // UPDATE
        if (!$empty_input) {
            // QUERY DE UPDATE - UPDATE EM QUAL TABELA, SETO OS VALORES PARA CADA COLUNA, ESPECIFICO EM QUAL ID
            $query_up_usuario = "UPDATE usuarios SET nome=:nome, email=:email WHERE id=:id";
            $edit_usuario = $conn->prepare($query_up_usuario);
            $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($edit_usuario->execute()){  
                $_SESSION['msg'] = "<p style='color: green;'>Usuário editado com sucesso!</p>";
                header('Location: index.php');
            } else {
                echo "<p style='color: #f00;'>Erro: Usuário não editado com sucesso!</p>";  
            }
            
        }
    }
    ?>
    
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="cadastrar.php">Cadastrar</a><br>
    <h1>Editar</h1>
    <form id = "edit-usuario" method="POST" action="">
        <label for="">Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Nome Completo" value="<?php
        
        if(isset($dados['nome'])) {
            echo $dados['nome'];
        } elseif (isset($row_usuario['nome'])) {
            echo $row_usuario['nome'];
        }?>"><br><br>

        <label for="">Email:</label>
        <input type="email" name="email" id="email" placeholder="Coloque seu melhor e-mail" value="<?php
        
        if (isset($dados['email'])){
            echo $dados['email'];
        } elseif (isset($row_usuario['email'])) {
            echo $row_usuario['email'];
        }?>"><br><br>
        
        <input type="submit" value="Salvar" name="EditUsuario">
    </form>
</body>
</html>