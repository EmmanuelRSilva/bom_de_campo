<?php

class Usuario{

	private $tabela = "usuario";
	private $id;
	private $nome;
	private $email;
	private $senha;
	private $nivel;

	function __construct($post){

		$this->setId($post);
		$this->setNome($post);
		$this->setEmail($post);
		$this->setSenha($post);
		$this->setNivel($post);

	}

	function setId($post){		
		if(isset($post['id'])){
			$this->id = trim(strip_tags($post['id']));
		}else{
			$this->id = "";
		}
	}

	function getId(){		
		return $this->id;
	}

	function setNome($post){		
		if(isset($post['nome'])){
			$this->nome = trim(strip_tags($post['nome']));
		}else{
			$this->nome = "";
		}
	}

	function getNome(){		
		return $this->nome;
	}

	function setEmail($post){		
		if(isset($post['email'])){
			if($post['tipo'] == "face"){
				$this->email = "f_".trim(strip_tags($post['email']));
			}else{
				$this->email = trim(strip_tags($post['email']));
			}
		}else{
			$this->email = "";
		}
	}

	function getEmail(){		
		return $this->email;
	}

	function setSenha($post){

		if(isset($post['senha'])){
			$this->senha = md5(trim(strip_tags($post['senha'])));
		}else{
			$this->senha = "";
		}
	}

	function getSenha(){		
		return $this->senha;
	}

	function setNivel($post){

		if(isset($post['nivel'])){
			$this->nivel = trim(strip_tags($post['nivel']));
		}else{
			$this->nivel = "0";
		}
	}

	function getNivel(){		
		return $this->nivel;
	}

	function getTabela(){		
		return $this->tabela;
	}


}


?>