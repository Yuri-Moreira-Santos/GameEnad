<?php
	// Configurações de conexão ao banco de dados
	$host = '192.168.15.199'; // Host do banco de dados, geralmente 'localhost'
	$database = 'gameenad'; // Nome do banco de dados
	$username = 'yuhdba'; // Usuário do banco de dados
	$password = 'IsawD4t$'; // Senha do banco de dados

	// Estabelecendo a conexão
	$conn = new mysqli($host, $username, $password, $database);

	// Verificando se a conexão foi bem-sucedida
	if ($conn->connect_error) {
		die("Falha na conexão com o banco de dados: " . $conn->connect_error);
	} 
?>