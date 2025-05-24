<?php
	function criptografarSenha($senha){
		return password_hash($senha, PASSWORD_DEFAULT);
	}
	
?>