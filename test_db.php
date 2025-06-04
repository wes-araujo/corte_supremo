<?php
// test_db.php

$servername = "localhost";
$username = "root";    
$password = "";        
$dbname = "restaurante_reservas"; 

echo "<h3>Teste de Conexão com Banco de Dados</h3>";
echo "Tentando conectar ao servidor MySQL (localhost)...<br>";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("<strong>Falha ao conectar ao SERVIDOR MySQL:</strong> " . $conn->connect_error . "<br>");
}
echo "Conexão com o SERVIDOR MySQL bem-sucedida!<br>";


echo "Tentando selecionar o banco de dados '$dbname'...<br>";
if ($conn->select_db($dbname)) {
    echo "<strong>Banco de dados '$dbname' selecionado com sucesso!</strong><br>";



} else {
    echo "<strong>FALHA ao selecionar o banco de dados '$dbname'.</strong> Erro do MySQL: " . $conn->error . "<br>";
}

$conn->close();
echo "<br>Teste finalizado.";
?>