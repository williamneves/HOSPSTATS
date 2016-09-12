<?php

require("security.php");
require("config.php");
require("functions.php");

$dados = $_REQUEST;

if($dados['Tabela'] == 'tarefa')
{
	$dataEN = dataBanco($dados['Data'],'EN');
	
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (SalaID,Data,Falta,NaoFezTarefa,NaoTrouxeMaterial,TarefaFazer) VALUES ('".$dados['Sala']."','".$dataEN." 00:00:00','".$dados['Falta']."','".$dados['NaoFezTarefa']."','".$dados['NaoTrouxeMaterial']."','".$dados['TarefaFazer']."')";
}

if($dados['Tabela'] == 'video')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (URL,SalaID,Data) VALUES ('".$dados['URL']."','".$dados['SalaID']."','".dataBanco($dados['Data'],'EN')." 00:00:00')";
}

if($dados['Tabela'] == 'agenda')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (Titulo,Descricao,SalaID,Data) VALUES ('".$dados['Titulo']."','".$dados['Descricao']."','".$dados['SalaID']."','".dataBanco($dados['Data'],'EN')." 00:00:00')";
}

if($dados['Tabela'] == 'clientes')
{
	(isset($dados['Site'])) ? $site = $dados['Site'] : $site = '#';
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,Tipo,Titulo,Documento,Documento2,Abertura,Aniversario,Telefone,Celular,Email,Site,Observacao) VALUES 
	('".$_SESSION['Empresa']['Dados']['AdminID']."','".$dados['Tipo']."','".strtoupper($dados['Titulo'])."','".preg_replace("/[^0-9]/", "", $dados['Documento'])."','".preg_replace("/[^0-9]/", "", $dados['Documento2'])."','".converteDataEN($dados['Abertura'])."','".converteDataEN($dados['Aniversario'])."','".preg_replace("/[^0-9]/", "", $dados['Telefone'])."','".preg_replace("/[^0-9]/", "", $dados['Celular'])."','".$dados['Email']."','".$site."','".$dados['Observacao']."')";
}

if($dados['Tabela'] == 'produtos')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,Codigo,Titulo,Grupo,ValorVenda,Observacao) VALUES 
	('".$_SESSION['Empresa']['Dados']['AdminID']."','".preg_replace("/[^0-9]/", "", $dados['Codigo'])."','".strtoupper($dados['Titulo'])."','".strtolower($dados['Grupo'])."','".preg_replace("/[^0-9]/", "", $dados['ValorVenda'])."','".$dados['Observacao']."')";
}

if($dados['Tabela'] == 'caixa')
{
	$item = $item = explode('@', $dados['Item']);	

	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,FuncionarioID,Titulo,Lancamento,Tipo,Item,Data,Valor) VALUES 
	('".$_SESSION['Empresa']['Dados']['AdminID']."','".$_SESSION['Empresa']['Dados']['ID']."','".$dados['Titulo']."','".$dados['Lancamento']."','".$item[1]."','".$item[0]."',now(),'".$dados['Valor']."')";

}

if($dados['Tabela'] == 'lembretes')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,DonoID,Tipo,Data,Status,Descricao,QtdeAvisos) VALUES 
	('".$_SESSION['Empresa']['Dados']['AdminID']."','".$dados['DonoID']."','bimestral','".converteDataEN($dados['Data'])."','".$dados['Status']."','".$dados['Descricao']."','0'), ('".$_SESSION['Empresa']['Dados']['AdminID']."','".$dados['DonoID']."','anual','".converteDataEN($dados['Data'])."','".$dados['Status']."','".$dados['Descricao']."','0')";
}

if($dados['Tabela'] == 'variaveis')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,Tipo,Titulo) VALUES ('".$_SESSION['Empresa']['Dados']['ID']."','".$dados['Tipo']."','".strtoupper($dados['Titulo'])."')";
}

if($dados['Tabela'] == 'enderecos')
{
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (ClienteID,Logradouro,Numero,Complemento,CEP,Bairro,Cidade,UF) VALUES 
	('".preg_replace("/[^0-9]/", "", $dados['ClienteID'])."','".strtoupper($dados['Logradouro'])."','".preg_replace("/[^0-9]/", "", $dados['Numero'])."','".strtoupper($dados['Complemento'])."','".preg_replace("/[^0-9]/", "", $dados['CEP'])."','".strtoupper($dados['Bairro'])."','".strtoupper($dados['Cidade'])."','".strtoupper($dados['UF'])."')";
}

if($dados['Tabela'] == 'categorias')
{
	$dados['Canonical'] = canonical($dados['Titulo']);
	// Motando a query
	$sql = "INSERT INTO ".$dados['Tabela']." (AdminID,Titulo,Canonical,Observacao) VALUES ('".$_SESSION['Empresa']['Dados']['AdminID']."','".strtoupper($dados['Titulo'])."','".strtolower($dados['Canonical'])."','".$dados['Observacao']."')";
}

$query = $Conexao->prepare($sql);

if ($query->execute())
{
	if(isset($dados['Diretorio']) AND $dados['Diretorio'] == 'sim')
	{
		// Pegando o último cliente ou fornecedor cadastrado para criar diretório
		$ID = $Conexao->lastInsertId();

		// Definindo parametros dos diretórios
		switch ($dados['Tipo'])
		{
			case 1: $pasta = 'clientes'; break;		// clientes
			case 2: $pasta = 'fornecedores'; break;	// fornecedores
			
			default: $pasta = 'empresa'; break;		// empresa do cliente (interno)
		}

		// definindo a pasta clientes, fornecedores ou empresa para o cliente
		$diretorio = "../arquivos/".$_SESSION['Empresa']['Dados']['ID']."/".$pasta."/";

		// Verificando se a pasta que está sendo criada já existe
		if (!file_exists($diretorio))
		{
			mkdir($diretorio, 0644, true); 	// criar diretório
			chmod($diretorio, 0644); 	// permissão diretório
		}

		$diretorio1 = "../arquivos/".$_SESSION['Empresa']['Dados']['ID']."/".$pasta."/".$ID."/";
		$diretorio2 = "../arquivos/".$_SESSION['Empresa']['Dados']['ID']."/".$pasta."/".$ID."/arquivos/";

		mkdir($diretorio1, 0644, true); // criar pasta do evento
		chmod($diretorio1, 0644); // permissão pasta do evento
		mkdir($diretorio2, 0644, true); // criar pasta do evento
		chmod($diretorio2, 0644); // permissão pasta do evento
	}

	echo '	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Parabéns!</strong> Adicionado com sucesso! </div>';

	if(isset($_REQUEST['Enderecos']))
	{
		($dados['Tipo'] == 1) ? $edit = 'editCliente' : $edit = 'editFornecedor';
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=".$edit."&ID=".$dados['ClienteID']."'>";
	}
	if(isset($_REQUEST['Clientes']))
	{
		($dados['Tipo'] == 1) ? $page = 'clientes' : $page = 'fornecedores';
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=".$page."'>";
	}
	if(isset($_REQUEST['Estoque']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=estoque'>";
	}
	if(isset($_REQUEST['Caixa']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=caixa'>";
	}
	if(isset($_REQUEST['Categoria']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=categorias'>";
	}
	if(isset($_REQUEST['Lembretes']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=lembretes&ID=".$dados['DonoID']."&Titulo=".$dados['Titulo']."'>";
	}
	if(isset($_REQUEST['Variavel']))
	{
		if ($dados['Tipo'] <> 11)
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=variaveis&Tipo=".preg_replace("/[^0-9]/", "", $dados['Tipo'])."&Titulo=".strtoupper($dados['TituloVariavel'])."'>";
		else
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=variaveis'>";
	}
}
else
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao adicionar. </div>';
}

?>