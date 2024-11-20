<?php
$host = "localhost";
$user = "root"; // Usuário do banco
$password = ""; // Senha do banco
$database = "hospital"; // Nome do banco de dados


$conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Conexão falhou. Erro: " . mysqli_connect_error());
    }
?>