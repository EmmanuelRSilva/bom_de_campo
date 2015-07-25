<?php

include 'classes/querys.php';
$conexao = new Query();
$conexao->logar();

if(isset($_GET['acao']) && strip_tags(trim($_GET['acao']) == "CARREGA JOGADOR")){
	
	$result['jogador'] = $conexao->detalharJogadores($_GET['id_jogador'],false);
	$result['comentarios'] = $conexao->procurarComentario($result['jogador'][0]['id']);
	$result['rodadas'] = $conexao->listarRodadas($result['jogador'][0]['id']);
	//retornoDeLog($result['rodadas']);
	$json_str = json_encode($result);
	echo $json_str;

}	


if(isset($_GET['acao']) && strip_tags(trim($_GET['acao']) == "LISTAR JOGADORES")){
	
	$result['jogadores'] = $conexao->listarJogadores(false,false);	
	//retornoDeLog($result['jogadores']);
	$json_str = json_encode($result);
	echo $json_str;

}	

	

?>
