var logado = null;
var nome = null;
var email = null;
var id = null;
var dadosEquipe = null
var id_jogador = 1;
var id_equipe = 19;

function carregaPagina(pagina,div,id){  
  id_equipe = id;
  id_jogador = id;
  $.ajax({
       url:pagina, 
       cache : false,
       type : "POST",        
       beforeSend : function() {
          //  faz a animação                    
          $(div).html("<div class='carregando'>carregando...</di>");
       },
       statusCode: {
          // identifica algum erro  e mostra como alerta ou dentro da div desejada                    
        404: function() { $(div).html("Arquivo nao foi encontrado!"); },
        500: function() { $(div).html("Falha de processamento!"); }
       },
       error : function(retorno) {
               //alert("erro:"+retorno);
       },
       success : function(retorno) {       
            $(div).html(retorno);          
       }
  });
}


function carrega(pagina,formulario,div,acao){   
  //console.debug($(formulario).serialize());
  var url = 'http://www.pix3.com.br/teste/bom_de_bola/servidor/'+pagina+'?acao='+acao
  if(id_jogador != null){
    url += '&id_jogador='+id_jogador
  }
  if(id_jogador != null){
    url += '&id_equipe='+id_equipe
  }
  $.ajax({
       url:url, 
       cache : false,
       type : "POST", 
       dataType: 'json',
       data : $(formulario).serialize(),
       beforeSend : function() {
          //  faz a animação                    
          $(div).html("<div class='carregando'>carregando...</di>");
       },
       statusCode: {
          // identifica algum erro  e mostra como alerta ou dentro da div desejada                    
        404: function() { $(div).html("Arquivo nao foi encontrado!"); },
        500: function() { $(div).html("Falha de processamento!"); }
       },
       error : function(retorno) {
               //alert("erro:"+retorno);
       },
       success : function(retorno) {             
            console.debug(retorno);

             dadosEquipe = retorno.equipe;

            if(acao == "LISTAR JOGADORES"){
              listarJogadores(retorno.jogadores);              
            } 

            if(acao == "CARREGA JOGADOR"){
              carregaJogador(retorno.jogador);
              carregaComentario(retorno.comentarios);
              carregaJogos(retorno.rodadas);
            } 

            if(acao == "LISTAR EQUIPES"){
              listarEquipes(retorno.equipe);              
            }
            if(acao == "CARREGA EQUIPE"){             
              carregarEquipes(retorno);
              carregaJogos(retorno.rodadas);
              carregaComentario(retorno.comentarios);              
            } 

            if(acao == "LISTAR CLASS"){
              listarClassificacao(retorno.equipe);                           
            } 

            if(acao == "CARREGA RODADAS"){
             listarRodadas(retorno.equipe);                      
            } 

            $(div).html("");          
       }
  });
}

function paginaInicial(){ 
  $("#containerPrincipal").css('display','none');
  $("#containarLogin").css('display','block');
  $.fancybox.close();
  logado = null;
  nome = null;
  email = null;
  id = null;
}


function carregaJogador(retorno){
  $("#fotoJoga").html("<img src='imagens/jogadores/"+retorno[0].id+".jpg'>");
  $(".nome_jogador").html(retorno[0].nome_jogador);
  $(".nome_equipe").html(retorno[0].nome_equipe);
  $(".cartoes_amarelos").html(retorno[0].cartoes_amarelos);
  $(".cartoes_vermelhos").html(retorno[0].cartoes_vermelhos);
  $(".num_faltas").html(retorno[0].num_faltas);  
  $(".peso_jogador").html(retorno[0].peso);
  $(".altura_jogador").html(retorno[0].altura);
  $(".pe_jogador").html(retorno[0].pe);
  $(".posicao_jogador").html(retorno[0].posicao);
  $(".modalidade_jogador").html(retorno[0].modalidade_equipe);  

}

function carregarEquipes(retorno){

  $("#fotoJoga").html("<img src='imagens/times/"+retorno.equipe[0].id+".jpg'>");
  $(".nome_equipe").html(retorno.equipe[0].nome);
  $(".cartao_amarelos").html(retorno.equipe[0].cartao_amarelo);
  $(".cartao_vermelhos").html(retorno.equipe[0].cartao_vermelho);
  $(".nome_tecnico").html(retorno.equipe[0].tecnico);
  $(".nome_representante").html(retorno.equipe[0].representante_equipe);

  $(".gols_time1").html(retorno.rodadas[0].gols_time1);
  $(".gols_time2").html(retorno.rodadas[0].gols_time2);
  $(".nome_time1").html(retorno.rodadas[0].time1);
  $(".nome_time2").html(retorno.rodadas[0].time2);
   

}


function carregaComentario(retorno){
    
    
    if(retorno == "0"){
       $('.itens_coments').html("<center>NÃO EXISTEM COMENTARIOS</center>"); 
    }else{
       for (var i = 0; i < retorno.length; i++) {

          var com = '<div class="geralComent">';
              com += '<div class="fontComent">';
              com += '<img src="imagens/jogadores/'+retorno[i].id_usuario+'.jpg" width="50px">';
              com += '</div>';
              com += '<div class="titNome">'+retorno[i].nome+'</div>';
              com += '<div class="comentJoga">'+retorno[i].comentario+'</div>';
              com += '</div>';
             
          $('.itens_coments').append(com);        
         
       };
    }

}

function carregaJogos(retorno){
   
    if(retorno ==  "0"){
       $('.lista_jogos').html("<center>NÃO EXISTEM JOGOS PREVISTOS</center>"); 
    }else{
      var com = "<select>";
      for (var i = 0; i < retorno.length; i++) {
        com += '<option>'+retorno[i].time1+' <strong> x </strong> '+retorno[i].time2+'</option>';            
      };
      com += "<select>";
      $(".lista_jogos").html(com);
    }

}

function listarEquipes(retorno){
   
    if(retorno ==  "0"){
       $('.lista_jogos').html("<center>NÃO EXISTEM EQUIPES CADASTRADAS!</center>"); 
    }else{
     
      for (var i = 0; i < retorno.length; i++) {

         var com = '<div class="boxList" onclick="carregaPagina(\'detalhe-time.html\',\'#centerSite\','+retorno[i].id+');">';
            com += '<div class="gFoto">';
            com += '<img src="imagens/times/'+retorno[i].id+'.jpg">';
            com += '</div>';
            com += '<div class="text-1">'+retorno[i].nome+'</div>';
            com += '<div class="f-boxM">1 LUGAR</div>';
            com += '<div class="f-boxM">29 PONTOS</div>';
            com += '<div class="aproveitamento">';
            com += '<b>69%</b> APROVEITAMENTO';
            com += '</div>';
            com += '</div>';

         $('.lista_equipes').append(com); 
      };     
      
    }

}


function listarJogadores(retorno){
   
    if(retorno ==  "0"){
       $('.lista_jogos').html("<center>NÃO EXISTEM JOGADORES CADASTRADOS!</center>"); 
    }else{
     
      for (var i = 0; i < retorno.length; i++) {

         var com = '<div class="boxList" onclick="carregaPagina(\'detalhe-jogador.html\',\'#centerSite\','+retorno[i].id+');">';
            com += '<div class="gFoto">';
            com += '<img src="imagens/jogadores/'+retorno[i].id+'.jpg">';
            com += '</div>'; 
            com += '<div class="text-1">'+retorno[i].nome_jogador+'</div>';
            com += '<div class="f-boxM">'+retorno[i].posicao+'</div>';
            com += '<div class="f-boxM"></div>';
            com += '<div class="aproveitamento">';
            com += '<b>69%</b> APROVEITAMENTO';
            com += '</div>';
            com += '</div>';
         $('.lista_jogadores').append(com); 
      }; 
     
    }

}

function listarClassificacao(retorno){
   
    if(retorno ==  "0"){
       $('.lista_equipes').html("<center>NÃO EXISTEM JOGADORES CADASTRADOS!</center>"); 
    }else{
     
      for (var i = 0; i < retorno.length; i++) {
        var com  = '<tr>';
            com += '<td>1</td>';
            com += '<td>'+retorno[i].nome+'</td>';
            com += '<td>28</td> ';
            com += '<td>18</td> ';
            com += '<td>5</td>  ';
            com += '<td>5</td>  ';
            com += '<td>58</td> ';
            com += '<td>24</td>';
            com += '</tr>';  
         $('.lista_equipes').append(com); 
      }; 
     
    }

}

function listarRodadas(retorno){
   
    if(retorno ==  "0"){
       $('.lista_rodada').html("<center>NÃO EXISTEM JOGADORES CADASTRADOS!</center>"); 
    }else{
     
      for (var i = 0; i < retorno.length; i++) {
        
        var com = '<div class="boxTime">';
            com += '<div class="time01">';
            com += '<img src="imagens/times/'+retorno[i].id_time1+'.jpg">';
            com += '<div class="n-boxM">'+retorno[i].time1+'</div>';
            com += '</div>';
            com += '<div class="versos">X</div>';
            com += '<div class="time01">';
            com += '<img src="imagens/times/'+retorno[i].id_time2+'.jpg">';
            com += '<div class="n-boxM">'+retorno[i].time2+'</div>';
            com += '</div>';
            com += '<div class="fontInf center c-verde-01">ESTÁDIO ADEMAR FERREIRA 20:30h</div>';
            com += '</div>';

         $('.lista_rodada').append(com); 
      }; 
     
    }

}





