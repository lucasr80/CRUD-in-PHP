<?php 
// MOSTRAR A MENSAGEM DE SUCESSO DO CADASTRO, COLOCADA EM UMA VARIAVEL GLOBAL
session_start();

// FUNCAO ob_start(); Para nao dar erro de direcionamento depois de cadastrar o usuario
// LIMPA o Buffer de saida
ob_start();

// INCLUI o banco de dados que se encontrar no arquivo conexao.php
include_once './conexao.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="cadastrar.php">Cadastrar</a><br>
    <h1>Cadastrar</h1>
    <?php
        // RECEBER OS DADOS DO FORMULARIO
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // VERIFICA SE O USUARIO CLICOU NO BOTAO DE CADASTRAR
        if(!empty($dados['CadUsuario'])) {
            // VALIDAR OS CAMPOS, COM PHP
            // Tirar espacos vazios no inicio e no Final - trim
            // Valida se o usuario deixou em branco o campo
            $empty_input = false; 

            $dados = array_map('trim', $dados);
            if(in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos os campos!</p>";
            } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher com email válido!</p>";
            }

            // Metodo nao adequado
            # $query_usuario = "INSERT INTO usuarios (nome, email) VALUES ('". $dados['nome'] . "', '". $dados['email'] ."') ";
            
            // Metodo adequado com bind param
            if(!$empty_input) {
                $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
                $cad_usuario = $conn->prepare($query_usuario);
                // Estancio o Bind Param, linkando o param :nome (parametro) com a chava do array ($dados) 'nome' e 'email' 
                $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                // PDO::PARAM_STR indica que o parametro e uma string
                $cad_usuario->execute();
                // APRESENTA O RESULTADO AO USUARIO
                if($cad_usuario->rowCount()) {
                    // Destroi as informacoes da variavel dados, e limpa o formulario
                    unset($dados);
                    // $_SESSION variavel globar, atribui em cochetes o nome da session
                    $_SESSION['msg'] = "<p style='color: green;'>Usuario cadastrado com sucesso!</p>";
                    header('Location: index.php');
                } else {
                    echo "<p style='color: #f00;'>Erro: Usuario nao cadastrado com sucesso!</p>";
                }
            }
        }
    ?>
    <form name="cad-usuario" method="POST" action="">
        <label for="">Nome: </label>
        <!- MANTER O VALOR INPUTADO PELO USUARIO NO VALUE -!>
        <input type="text" name="nome" id="nome" placeholder="Nome Completo" value="<?php 
        if(isset($dados['nome'])){
            echo $dados['nome'];
        }
        ?>"><br><br>
        <label for="">Email: </label>
        <input type="email" name="email" id="email" placeholder="Seu melhor email" value="<?php 
        if(isset($dados['email'])){
            echo $dados['email'];    
        }
        ?>"><br><br>
        <input type="submit" value="Cadastrar" name="CadUsuario">
    </form>
</body>
</html>