//INICIALIZA AS VARIAVEIS PARA CONTROLE DA FILA
var ifila = 0
var fila = new Array();
//fila[X][0] - Div onde sera carregada a pagina.
//fila[X][1] - Pagina que sera chamada
//fila[X][2] - Div onde sera exibido o carregando avulso ( Se nao declarada sera na mesma div que sera carregada a pagina)
//fila[X][3] - Metodo de envio
//fila[X][4] - Campos do form concatenados no padrao para serem enviados. Null caso seja um link
//fila[X][5] - Funcao que sera executada ao termino do processo. Null caso seja um ajaxLink/ajaxForm



//INICIALIZA O OBJETO QUE IRA FAZER AS SOLICITACOES
try{
		xmlhttp = new XMLHttpRequest();// Mozilla, Safari, Firefox, etc...
		try {
				if (xmlhttp.overrideMimeType) {
						//Se possível, ignora cabecalho usado pelo servidor e forca o padrao "text/xml". Alguns navegadores exigem esse padrao e pode dar erro se o servidor nao utilizar ele
						xmlhttp.overrideMimeType('text/xml');
				}
		} catch (e1) { }
}catch(e2){
		try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");// Internet Explorer
		}catch(e3){
				try{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");// Internet Explorer
				}catch(e4){
						//tratamento para alguma outra forma de implementar XMLHTTP
						xmlhttp = false;
				}
		}
}
if (!xmlhttp){
		//Nao conseguiu instanciar o objeto xmlhttp para fazer as solicitacoes
		alert("AJAX error. Your browser must support XMLHttpRequest object or enable use of scripting.");
}



//GUARDA NA FILA O ID DO OBJETO E A URL QUE SERAO CARREGADOS PELO LINK CLICADO
function AjaxLink(id_target,url,carregando){
		//Exibe mensagem de que esta carregando a pagina no objeto de ID informado
		ajaxMensagemCarregando(id_target,carregando);

		//Adiciona a solicitacao na fila
		fila[fila.length]=[id_target,url,carregando,"GET",null,null];

		//Se nao tem conexoes na fila, inicia a execucao
		if(fila.length==1){
				ajaxRun();
		}
		return;
}



//GUARDA NA FILA O ID DO OBJETO E O FORM QUE SERAO CARREGADOS PELO LINK CLICADO
function AjaxForm(id_target,id_form,url,carregando){
		//Busca metodo de envio definido no form
		var metodoEnvio = document.getElementById(id_form).method.toUpperCase();
		//Busca os elementos do form que serao enviados para a pagina solicitada
		var elementos_form = BuscaElementosForm(id_form);

		//Exibe mensagem de que esta carregando a pagina no objeto de ID informado
		ajaxMensagemCarregando(id_target,carregando);

		//Adiciona a solicitacao na fila
		fila[fila.length]=[id_target,url,carregando,metodoEnvio,elementos_form,null];

		//Se nao tem conexoes na fila, inicia a execucao
		if(fila.length==1){
				ajaxRun();
		}
		return;
}
function AjaxFunction(id_target,funcao,url,carregando){
		//Adiciona a solicitacao na fila
		fila[fila.length]=[id_target,url,carregando,"GET",null,funcao.replace('()',"")];

		//Se nao tem conexoes na fila, inicia a execucao
		if(fila.length==1){
				ajaxRun();
		}
		return;
}



//EXECUTA A PROXIMA SOLICITACAO DA FILA
function ajaxRun(){
		var url = fila[ifila][1];

		//Define o metodo de envio (GET ou POST)
		var metodoEnvio;
		if (fila[ifila][4]==null){
				//Se for Link/Funcao, utiliza GET
				metodoEnvio = "GET";
		}else{
				//Se for Form, define o metodo de envio e prepara a url
				metodoEnvio = fila[ifila][3];
				if (metodoEnvio=="" || metodoEnvio==null){
						//Se nao tiver definido nada, usa POST
						metodoEnvio = "POST";
				}
				if (metodoEnvio=="GET"){
						//Metodo GET passa as informacoes na linha da url
						url = url + "?" + fila[ifila][4];
				}
		}

		//Abre a conexao
		xmlhttp.open(metodoEnvio,url,true);

		//Seta as funcoes que irao tratar a mudanca de estado do objeto XMLHTTP
		xmlhttp.onreadystatechange=ajaxXMLHTTP_StateChange;

		//Executa a solicitacao
		if (metodoEnvio=="POST"){
				//Metodo POST precisa definir este RequestHeader
				xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				//Metodo POST passa as variaveis pelo metodo Send
				xmlhttp.send(fila[ifila][4]);
		}else{
				xmlhttp.send(null);
		}
		return;
}



//FUNCAO EXECUTADA QUANDO ALTERAR O STATUS DA SOLICITACAO (readyState)
function ajaxXMLHTTP_StateChange() {
		//0-Nao inicializado, 1-Carregando, 2-Carregado, 3-Interativo, 4-Completo
		if (xmlhttp.readyState==1){
				ajaxXMLHTTP_StateChange_Carregando(fila[ifila][0], fila[ifila][2], fila[ifila][5]);//Quando iniciar a solicitacao
		}else{
				if (xmlhttp.readyState==4){
						ajaxXMLHTTP_StateChange_Completo(xmlhttp, fila[ifila][0], fila[ifila][2], fila[ifila][5]);//Quando estiver completa a solicitacao
				}
		}
}

//FUNCAO EXECUTADA QUANDO INICIAR A SOLICITACAO (readyState=1)
function ajaxXMLHTTP_StateChange_Carregando(id, carregando, funcao){
		if(funcao==null){
				//Exibe mensagem de que está carregando a página no objeto de ID que solicitacao esta sendo feita
				ajaxMensagemCarregando(id,carregando);
		}
		return;
}

//FUNCAO EXECUTADA QUANDO A SOLICITACAO ESTIVER COMPLETA (readyState=4)
function ajaxXMLHTTP_StateChange_Completo(xmlhttp, id_retorno, carregando, funcao){
		var retorno;

		//Verifica o status da pagina de retorno
		if (xmlhttp.status == 200 || xmlhttp.status==0) {
				//Caso o status seja 200(Sucesso) ou nao utilize servidor(chamada local [C:\...]), trata o valor retornado
				retorno=xmlhttp.responseText;
//              retorno=retorno.replace(/\+/g," ");
				retorno=unescape(retorno);
		}else{
				//Caso o status ainda nao foi tratado, chama a funcao de tratamento de pagina de erro
				retorno=ajaxPaginaErro(xmlhttp);
		}
		if(funcao==null){
				// Se for declaro um carregando avulso, quando a pagina for carregada ele sumira
				if(carregando != null)
					document.getElementById(carregando).innerHTML="";
					
				//Exibe o valor retornado no objeto de ID informado
				document.getElementById(id_retorno).innerHTML=retorno;
		}else{
				//Se definiu uma funcao, executa esta funcao
				eval(funcao+"('"+id_retorno+"','"+retorno+"');");
		}
		// executa scripts
		ExtraiScript(retorno);

		//passa para a proxima posicao da fila
		ifila++;
		if(ifila<fila.length){
				//Caso tenha mais solicitacoes na fila, executa a proxima
				setTimeout("ajaxRun()",20);
		}else{
				//Caso nao tenha mais solicitacoes na fila, reinicia a fila
				fila = null;
				fila = new Array();
				ifila = 0;
		}
		return;
}

//FUNCAO PARA RETORNAR A MENSAGEM DE ERRO QUANDO O SERVIDOR RETORNAR UMA PAGINA DE ERRO
function ajaxPaginaErro(xmlhttp){
		var retorno;
		switch (xmlhttp.status) {
				case 404:
						return "Página não encontrada!!!";
						break;
				case 500:
						return "Erro interno do servidor!!!";
						break;
				default:
						return "Erro desconhecido!!!<br>" + xmlhttp.status + " - " + xmlhttp.statusText.replace(/\+/g," ");
		}
}

//FUNCAO PARA RETORNAR A MENSAGEM DE QUE ESTA CARREGANDO A PAGINA
function ajaxMensagemCarregando(id,carregando)
{
	if(carregando != null)
	{
		document.getElementById(carregando).innerHTML = "<img src='images/load.gif' alt='Carregando...' />";
	}
	else
	{
		document.getElementById(id).innerHTML = '<div style="display: table;" class="loader text-center"><div class="loader-inner ball-scale-random"><div></div><div></div><div></div></div></div>';
	}
}

//FUNCAO PARA PEGAR OS CODIGOS SCRIPT
function ExtraiScript(texto){
		var ini, pos_src, fim, codigo, texto_pesquisa;
		var objScript = null;
		//Joga na variavel de pesquisa o texto todo em minusculo para na hora da pesquisa nao ter problema com case-sensitive
		texto_pesquisa = texto.toLowerCase()
		// Busca a primeira tag <script
		ini = texto_pesquisa.indexOf('<script', 0)
		// Executa o loop enquanto achar um <script
		while (ini!=-1){
				//Inicia o objeto script
				var objScript = document.createElement("script");

				//Busca se tem algum src a partir do inicio do script
				pos_src = texto_pesquisa.indexOf(' src', ini)
				// Define o inicio para depois do fechamento dessa tag
				ini = texto_pesquisa.indexOf('>', ini) + 1;

				//Verifica se este e um bloco de script ou include para um arquivo de scripts
				if (pos_src < ini && pos_src >=0){//Se encontrou um "src" dentro da tag script, esta e um include de um arquivo script
						//Marca como sendo o inicio do nome do arquivo para depois do src
						ini = pos_src + 4;
						//Procura pelo ponto do nome da extencao do arquivo e marca para depois dele
						fim = texto_pesquisa.indexOf('.', ini)+4;
						//Pega o nome do arquivo
						codigo = texto.substring(ini,fim);
						//Elimina do nome do arquivo os caracteres que possam ter sido pegos por engano
						codigo = codigo.replace("=","").replace(" ","").replace("\"","").replace("\"","").replace("\'","").replace("\'","").replace(">","");
						// Adiciona o arquivo de script ao objeto que sera adicionado ao documento
						objScript.src = codigo;
				}else{//Se nao encontrou um "src" dentro da tag script, esta e um bloco de codigo script
						// Procura o final do script
						fim = texto_pesquisa.indexOf('</script>', ini);
						// Extrai apenas o script
						codigo = texto.substring(ini,fim);
						// Adiciona o bloco de script ao objeto que sera adicionado ao documento
						objScript.text = codigo;
				}

				//Adiciona o script ao documento
				document.body.appendChild(objScript);
				// Procura a proxima tag de <script
				ini = texto.indexOf('<script', fim);

				//Limpa o objeto de script
				objScript = null;
		}
}



//FUNCAO PARA PEGAR OS ELEMENTOS DO FORM
function BuscaElementosForm(idForm) {
		var elementosFormulario = document.getElementById(idForm).elements;
		var qtdElementos = elementosFormulario.length;
		var queryString = "";
		var elemento;

		//Cria uma funcao interna para concatenar os elementos do form
		this.ConcatenaElemento = function(nome,valor) {
																if (queryString.length>0) {
																		queryString += "&";
																}
																//queryString += encodeURIComponent(nome) + "=" + encodeURIComponent(valor);
																queryString += encodeURI(nome) + "=" + encodeURI(valor).replace("&", "%26");
														 };

		//Loop para percorrer todos os elementos
		for (var i=0; i<qtdElementos; i++) {
				//Pega o elemento
				elemento = elementosFormulario[i];
				if (!elemento.disabled) {
						//Trabalha com o elemento caso ele nao esteja desabilitado
						switch(elemento.type) {
								//Realiza a acao dependendo do tipo de elemento
								case 'text': case 'password': case 'hidden': case 'textarea':
										this.ConcatenaElemento(elemento.name,elemento.value);
										break;
								case 'select-one':
										if (elemento.selectedIndex>=0) {
												this.ConcatenaElemento(elemento.name,elemento.options[elemento.selectedIndex].value);
										}
										break;
								case 'select-multiple':
										for (var j=0; j<elemento.options.length; j++) {
												if (elemento.options[j].selected) {
														this.ConcatenaElemento(elemento.name,elemento.options[j].value);
												}
										}
										break;
								case 'checkbox': case 'radio':
										if (elemento.checked) {
												this.ConcatenaElemento(elemento.name,elemento.value);
										}
										break;
						}
				}
		}
		return queryString;
}