<?php 
// INICIO A SESSION para a variavel global
session_start();

// CONECTO com o Banco de Dados
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar</title>
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="cadastrar.php">Cadastrar</a><br>
    <h1>Listar</h1>

    <?php
    
    if(isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    
    # Paginacao, receber numero da pagina
    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
    // Se a pagina estiver vazio atribui o valor da pagina atual, caso esteja vazio tbm atribui o valor 1.
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
    
    # var_dump usado para verificar os dados
    //var_dump($pagina);

    // SETAR a quantidade de registros
    $limite_resultado = 40;

    // CALCULAR o inicio da visualizacao
    $inicio = ($limite_resultado * $pagina) - $limite_resultado;
    // EXEMPLO quero limite de 2 registros e multiplico pela pagina que o usuario esta
    // Pagina 3 entao 2 * 3, entao o registro que vai comecar e o 5 e vai ate o 6.
    // POR fim voce subtrai - 1 - 1,2 2 - 3,4 3 - 5,6 e assim por diante
    // LOGO APOS IMPLEMENTO NA QUERY

    # SELECIONE as tabelas id, nome e email na tabela usuarios
    # PARA trazer por ultimo registro cadastrado usa-se ORDER BY de forma DESC decrescente
    # $query_usuarios = "SELECT id, nome, email FROM usuarios ORDER BY id DESC LIMIT $inicio, $limite_resultado";
    $query_usuarios = "SELECT id, nome, email FROM usuarios LIMIT $inicio, $limite_resultado";
    $result_usuarios = $conn->prepare($query_usuarios);
    $result_usuarios->execute();
    
    // LISTAGEM
    if(($result_usuarios) AND ($result_usuarios->rowCount() !=0 )) {
        # LOOP lendo as linhas do banco de dados
        while($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)){
            # Verifica se realmente esta lendo os dados
            // var_dump($row_usuario);
            extract($row_usuario);
            // -> Uma das maneiras de mostrar echo "ID: " . $row_usuario['id'] . "<br>";
            
            // FORMA COM EXTRACT
            echo "ID: $id <br>";
            echo "Nome: $nome <br>";
            echo "E-mail: $email <br><br>";
            echo "<a href='visualizar.php?id=$id'>Visualizar</a><br>";
            echo "<a href='editar.php?id=$id'>Editar</a><br>";
            echo "<a href='apagar.php?id=$id'>Apagar</a><br>";
            echo "<hr>";
        } 

        // SOMENTE se encontrar registro no Banco de Dados, Contar a quantidade de registros.
        $query_qnt_registros = "SELECT COUNT(id) AS num_result FROM usuarios";
        $result_qnt_registros = $conn->prepare($query_qnt_registros);
        $result_qnt_registros->execute();
        
        // LER O VALOR
        $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);
        
        // QUANTIDADE de paginas que tem no projeto
        // CEIL usado para arredondar o valor retornado de paginas
        $qnt_pagina = ceil($row_qnt_registros['num_result'] /$limite_resultado);
        
        // MAXIMO de links setados, na paginacao
        $maximo_link = 2;

        // IMPLANTAR paginacao
        echo "<a href='index.php?page=1'>Primeira </a>";

        // DUAS paginas antes da ultima
        // FOR Inicio, condicao o incremento
        for($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
            if($pagina_anterior >= 1) {
                echo "<a href='index.php?page=$pagina_anterior'> $pagina_anterior </a>" ;
            }
        }

        echo "$pagina";

        // IMPRIMIR as proximas paginas
        for($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++ ) {
            // QUANDO nao existe as duas paginas posteriores, a proxima pagina so aparece se for menor que a quantidade de paginas existentes
            if($proxima_pagina <= $qnt_pagina) {
                echo "<a href='index.php?page=$proxima_pagina'> $proxima_pagina <a/>";
            }
        }

        echo "<a href='index.php?page=$qnt_pagina'> Última</a>";
    } else {
        echo "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
    }
    
    ?>
</body>
</html>