<?php

include 'classes/querys.php';
$conexao = new Query();
$conexao->logar();

if(isset($_GET['acao']) && strip_tags(trim($_GET['acao'])) == "LISTAR EQUIPES"){
	
	$result['equipe'] = $conexao->procurarEquipes();
	//retornoDeLog($result); 
	$json_str = json_encode($result);	
	echo $json_str;

}

if(isset($_GET['acao']) && strip_tags(trim($_GET['acao'])) == "LISTAR CLASS"){
	
	$result['equipe'] = $conexao->procurarEquipes();
	//retornoDeLog($result); 
	$json_str = json_encode($result);	
	echo $json_str;

}

if(isset($_GET['acao']) && strip_tags(trim($_GET['acao'])) == "CARREGA RODADAS"){
	
	$result['equipe'] = $conexao->listarTodasRodadas();
	//retornoDeLog($result); 
	$json_str = json_encode($result);	
	echo $json_str;

}


if(isset($_GET['acao']) && strip_tags(trim($_GET['acao'])) == "CARREGA EQUIPE"){
	
	$result['equipe'] = $conexao->detalharEquipes($_GET['id_equipe'],1);
	$result['rodadas'] = $conexao->listarRodadas($result['equipe'][0]['id_jogador']);
	$result['comentarios'] = $conexao->procurarComentario($result['equipe'][0]['id']);
	//retornoDeLog($result); 
	$json_str = json_encode($result);	
	echo $json_str;

}



?>
