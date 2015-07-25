<?php
include 'classes/querys.php';
include 'classes/classe_usuario.php';
$conexao = new Query();
$conexao->logar();
$usuario = new Usuario($_REQUEST);

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == "LOGAR"){
	
	// REGISTRA UM LOG
	
	$result = $conexao->procurarUsuario($usuario->getEmail(),$usuario->getSenha(),false); // login,senha,limit	
	$json_str = json_encode($result,JSON_PRETTY_PRINT);
	echo $json_str;
		
}	

?>