<?php
	// Configurações de conexão ao banco de dados
	$host = 'localhost'; // Host do banco de dados, geralmente 'localhost'
	$username = 'root'; // Usuário do banco de dados
	$password = ''; // Senha do banco de dados
	$database = 'gameenad'; // Nome do banco de dados

	// Estabelecendo a conexão
	$conn = new mysqli($host, $username, $password, $database);

	// Verificando se a conexão foi bem-sucedida
	if ($conn->connect_error) {
		die("Falha na conexão com o banco de dados: " . $conn->connect_error);
	} 
?>