<?php

session_start();

ob_start();

include_once './conexao.php';

// RECEBO O ID PELA URL
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

# var_dump($id);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header('Location: index.php');
    // EXIT PARA O FUNCIONAMENTO
    exit();
}

// VERIFICO SE EXISTE O USUARIO DO BANCO DE DADOS
$query_usuario = "SELECT id FROM usuarios WHERE id = $id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

// VERIFICAR SE ENCONTROU UM USUARIO NO BANCO DE DADOS
if (($result_usuario) AND ($result_usuario->rowCount() != 0 )) {
    $query_del_usuario = "DELETE FROM usuarios WHERE id = $id";
    $apagar_usuario = $conn->prepare($query_del_usuario);
    
    if ($apagar_usuario->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>Usuário deletado com sucesso!</p>";
        header('Location: index.php'); 
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Não foi possivel apagar o Usuário!</p>";
        header('Location: index.php'); 
    }

} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header('Location: index.php'); 
}
?>