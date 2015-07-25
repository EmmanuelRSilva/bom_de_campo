<?php

class Query{	

	//senha do cpanel: Mariana1

	private $conecta;
	private $conexao;	

	function logar(){

		if(!defined("HOST")){
		define('HOST','localhost');		
		}
		if(!defined("DB")){
		define('DB','pix3_bom_de_bola');
		//define('DB','bom_de_bola');
		}
		if(!defined("USER")){
		define('USER','pix3_emmanuel');
		//define('USER','root');
		}
		if(!defined("PASS")){
		define('PASS','pixEmmanuel123!');
		//define('PASS','');
		}			
		$this->conexao = 'mysql:host='.HOST.';dbname='.DB;									
		try{
			$this->conecta = new PDO($this->conexao,USER,PASS);
			$this->conecta->exec('set names utf8');
			$this->conecta->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				
		} catch (PDOexception $error_conecta) {
			echo htmlentities('Erro ao conectar'.$error_conecta->getMessage());
		}

	}

	function paginador($tabela,$id,$inicio,$lpp,$nivel = null,$id_usuario = null){

		$sql_select = 'SELECT * FROM '.$tabela.' WHERE id != 0 ';
		if($nivel != null){
			$sql_select .= 'AND nivel in ('.$nivel.') ';	
		}
		if($id_usuario != null){
			$sql_select .= 'AND id_usuario="'.$id_usuario.'" ';	
		}
		$sql_select .= 'ORDER BY '.$id.' DESC LIMIT '.$inicio.','.$lpp;
		//die($sql_select);			
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count == '0'){
			 return(false);// echo '<h2 class="f28"><strong class="cinza1">OPS!!&nbsp;</strong> <strong class="vermelho">NADA FOI ENCONTRADO.</strong></h2>';
			} else {
				return($resultado_query);
			}	
		} catch (PDOexception $error_select){
			echo 'Erro ao selecionar'.$error_select->getMessage();
		}

		
	}

	function inserir($tabela, $dados){

		// pega campos da array
		$arrCampo = array_keys($dados);		
		// pega valores da array
		$arrValores = array_values($dados);
		// conta campos da array
		$numCampo = count($dados);
		//conta valores array
		$numValores = count($dados);

		if ($numCampo == $numValores){
			$sql_inserir = "INSERT INTO ".$tabela." (";
			foreach($arrCampo as $campo){
				$sql_inserir.="$campo,";	
			}
			$sql_inserir = substr_replace($sql_inserir, ")", -1, 1);
			$sql_inserir.= "VALUES (";
			foreach($arrValores as $valores){
				$sql_inserir.="'".$valores."',";	
			}
			$sql_inserir = substr_replace($sql_inserir, ")", -1, 1);
			
			//die($sql_inserir)	;

		}
				
		try{
			$query_inserir = $this->conecta->prepare($sql_inserir);
			for($cont = 0;$cont < $numCampo; $cont++ ){				
				$query_inserir->bindValue("'".$arrCampo[$cont]."'",$arrValores[$cont],PDO::PARAM_STR);
			}
			$query_inserir->execute();
			$result['result'] = "ok";						
			return($result);		
		} catch (PDOexception $error_insert){
			$result['result'] = 'Erro ao Cadastrar'.$error_insert->getMessage();
			return($result);
		}
	
		

	}

	function excluir($tabela,$id){

		$sql_excluir = 'DELETE FROM '.$tabela.' WHERE id = '.$id;
		// die($sql_excluir);						
		try{
			$query_excluir= $this->conecta->prepare($sql_excluir);
			$query_excluir->bindValue('id',$id,PDO::PARAM_INT);			
			$query_excluir->execute();
			$erro = "00";			
			return($erro);
		} catch (PDOexception $error_insert){
			$erro ='Erro ao Editar'.$error_insert->getMessage();
			return($erro);
		}
	

	}

	function procurarUsuario($login = false,$senha = false, $limit = false){		
	
		$sql_select = "SELECT * FROM usuario ";

		if($login){
			$sql_select .= "WHERE login='".$login."' ";
		}

		if($senha){
			$sql_select .= "AND senha='".$senha."' ";
		}

		if($limit){
			$sql_select .= "limit ".$limit." ";
		}

						
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				$resultado_query['error'] = "NÃO EXISTE RESULTADO PARA A BUSCA!";
				return($resultado_query);
			}	
		} catch (PDOexception $error_select){
			$resultado_query['error'] = $error_select->getMessage();
			return($resultado_query);
		}		

	}

	function listarJogadores($id = false, $limit = false){

		$sql_select  = "SELECT * ";		
		$sql_select  .= "FROM jogadores jog, detalhe_jogador det "; 
		$sql_select  .= "WHERE jog.id=det.id_jogador "; 
		
		if($id){
			$sql_select  .= "AND jog.id='".$id."' ";
		}
		if($limit){
			$sql_select .= "limit ".$limit." ";
		}
		//$this->retornoDeLog($sql_select);
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		
	}

	
	function detalharJogadores($id = false, $limit = false){

		$sql_select  = "SELECT jog.id, jog.modalidade_equipe,  ";
		$sql_select  .= "jog.nome_equipe, ";
		$sql_select  .= "jog.representante_equipe, ";
		$sql_select  .= "jog.tecnico, ";
		$sql_select  .= "jog.nome_jogador, ";
		$sql_select  .= "jog.numero_jogador, ";
		$sql_select  .= "jog.bairro_jogador, ";
		$sql_select  .= "jog.cidade_jogador, ";
		$sql_select  .= "jog.dtnascimento_jogador, ";
		$sql_select  .= "pont.num_gols, ";
		$sql_select  .= "pont.num_faltas, ";
		$sql_select  .= "pont.cartoes_amarelos, ";
		$sql_select  .= "pont.cartoes_vermelhos, ";
		$sql_select  .= "det.peso, ";
		$sql_select  .= "det.altura, ";
		$sql_select  .= "det.pe, ";
		$sql_select  .= "det.posicao FROM jogadores jog, pontuacao_jogador pont, detalhe_jogador det "; 
		$sql_select  .= "WHERE jog.id = pont.id_jogadores "; 
		$sql_select  .= "AND jog.id=det.id_jogador ";
		if($id){
			$sql_select  .= "AND jog.id='".$id."' ";
		}
		if($limit){
			$sql_select .= "limit ".$limit." ";
		}

		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		
	}

	function detalharEquipes($id, $limit = false){

		$sql_select  = "SELECT equi.id, equi.nome, ";
		$sql_select  .= "equi.categoria, ";
		$sql_select  .= "equi.cartao_vermelho, ";
		$sql_select  .= "equi.cartao_amarelo, ";	
		$sql_select  .= "jog.representante_equipe, ";
		$sql_select  .= "jog.tecnico, ";	
		$sql_select  .= "jog.id as id_jogador ";		
		$sql_select  .= "FROM equipes equi, jogadores jog "; 
		$sql_select  .= "WHERE trim(equi.categoria) = trim(jog.modalidade_equipe) "; 
		$sql_select  .= "AND trim(equi.nome) = trim(jog.nome_equipe) "; 
		$sql_select  .= "AND equi.id = '".$id."' ";
		//$this->retornoDeLog($sql_select); 
		if($limit){
			$sql_select .= "limit ".$limit." ";
		}

		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		
	}



	function listarRodadas($id, $limit = false){

		$sql_select   = "SELECT gols_time1, ";
		$sql_select  .= "gols_time2, ";
		$sql_select  .= "(SELECT nome FROM equipes WHERE id=id_time1) as time1, ";
		$sql_select  .= "(SELECT nome FROM equipes WHERE id=id_time2) as time2 ";
		$sql_select  .= "FROM jogos, ";
		$sql_select  .= "(SELECT id as id_time FROM equipes WHERE nome = (SELECT nome_equipe FROM jogadores WHERE id =  '".$id."' LIMIT 1 )) as filtro ";
		$sql_select  .= "WHERE id_time1 = id_time OR id_time2=id_time; ";
		
		//$this->retornoDeLog($sql_select);
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		
	}

	function listarTodasRodadas($limit = false){

		$sql_select   = "SELECT gols_time1, ";
		$sql_select  .= "gols_time2, ";
		$sql_select  .= "id_time1, ";
		$sql_select  .= "id_time2, ";
		$sql_select  .= "(SELECT nome FROM equipes WHERE id=id_time1) as time1, ";
		$sql_select  .= "(SELECT nome FROM equipes WHERE id=id_time2) as time2 ";
		$sql_select  .= "FROM jogos ";
		
		
		//$this->retornoDeLog($sql_select);
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		
	}



	function procurarComentario($id_usuario){		
	
		$sql_select = "SELECT com.id_usuario, com.id_destinatario, com.comentario, 
					   (SELECT nome_jogador FROM jogadores WHERE id=com.id_usuario ) AS nome 
					   FROM comentario com 
					   WHERE id_destinatario='".$id_usuario."';";

		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		

	}

	function procurarEquipes($id = false){		
	
		$sql_select  = "SELECT equi.id, equi.nome FROM  equipes equi, jogos jog ";
		$sql_select .= "WHERE equi.id = jog.id_time1 OR equi.id = jog.id_time2 ";

		if($id){
			$sql_select .= "WHERE id='".$id."' ";
		}

		$sql_select .= "GROUP BY nome ORDER BY id";
		try{
			$query_select = $this->conecta->prepare($sql_select);
			$query_select->execute();
			$resultado_query = $query_select->fetchALL(PDO::FETCH_ASSOC);
			$count = $query_select->rowCount(PDO::FETCH_ASSOC);	
			if($count != 0){
				return($resultado_query);
			}else{
				return($resultado_query['error'] = "0");
			}	
		} catch (PDOexception $error_select){
			return($resultado_query['error'] = 'Erro ao selecionar'.$error_select->getMessage());
		}		

	}


	function retornoDeLog($texto,$titulo = "LogMonitoramento"){    
 
	  date_default_timezone_set('America/Recife');
	  $fp = fopen($titulo.".txt", "a");    
	  $texto = print_r($texto,true);  
	  $escreve = fwrite($fp,$texto);
	  $linha  = "\n\n*-----------------------------------------------------------------------------------------------------------------------------------*\n";
	  $linha .= "*-----------------------------------------".date("D M j G:i:s T Y")."----------------------------------------------------------";
	  $linha .= "\n*-----------------------------------------------------------------------------------------------------------------------------------*\n \n";
	  $escreve = fwrite($fp,$linha);                     
	  fclose($fp);
	 
	}



} // fim classe

function retornoDeLog($texto,$titulo = "LogMonitoramento"){    
 
  date_default_timezone_set('America/Recife');
  $fp = fopen($titulo.".txt", "a");    
  $texto = print_r($texto,true);  
  $escreve = fwrite($fp,$texto);
  $linha  = "\n\n*-----------------------------------------------------------------------------------------------------------------------------------*\n";
  $linha .= "*-----------------------------------------".date("D M j G:i:s T Y")."----------------------------------------------------------";
  $linha .= "\n*-----------------------------------------------------------------------------------------------------------------------------------*\n \n";
  $escreve = fwrite($fp,$linha);                     
  fclose($fp);
 
}

?>