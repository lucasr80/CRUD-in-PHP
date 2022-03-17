<?php

// DADOS PARA CONEXAO COM BANDO DE DADOS
$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "cadyoutube";
$port = 3306;

// CONEXAO COM A PORTA
$conn = new PDO ("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

// CONEXAO SEM PORTA
# $conn = new PDO ("mysql:host=$host;dbname=" . $dbname, $user, $pass);


?>