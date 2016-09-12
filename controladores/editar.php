<?php
require("security.php");
require("config.php");
require("functions.php");

$dados = $_REQUEST;

if( isset($_REQUEST['Lembretes']) AND ($_REQUEST['Lembretes'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Lembrete não foi '.$dados['Status'].'. </div>';
	exit();
}

// Cadastrando novo vídeo
if($dados['Tabela'] == 'dados')
{
	// Motando a query
	$sql = "UPDATE  ".$dados['Tabela']." SET  Telefone =  '".$dados['Telefone']."', Telefone2 =  '".$dados['Telefone2']."', Celular =  '".$dados['Celular']."', Email =  '".$dados['Email']."', Numero =  '".$dados['Numero']."', Bairro =  '".$dados['Bairro']."' WHERE ID = 1";
}

if ($dados['Tabela'] == 'clientes')
{
	(isset($dados['Site'])) ? $site = $dados['Site'] : $site = '#';
	$sql = "UPDATE  ".$dados['Tabela']." SET  Titulo =  '".strtoupper($dados['Titulo'])."', Documento =  '".preg_replace("/[^0-9]/", "", $dados['Documento'])."', Documento2 =  '".preg_replace("/[^0-9]/", "", $dados['Documento2'])."', Abertura =  '".converteDataEN($dados['Abertura'])."', Aniversario =  '".converteDataEN($dados['Aniversario'])."', Telefone =  '".preg_replace("/[^0-9]/", "", $dados['Telefone'])."', Celular =  '".preg_replace("/[^0-9]/", "", $dados['Celular'])."', Email =  '".strtolower($dados['Email'])."', Site =  '".strtolower($site)."', Observacao =  '".$dados['Observacao']."' WHERE ID = " . $dados['ClienteID'];
}

if ($dados['Tabela'] == 'lembretes')
{
	// editar lembrete
	if (isset($_REQUEST['AtualizarLembrete']))
	{
		$sql = "UPDATE  ".$dados['Tabela']." SET Descricao = '".$dados['Descricao']."', Data = '".converteDataEN($dados['Data'])."'  WHERE ID = '".$dados['LembreteID']."'";
	}
	// renovar, cancelar, finalizar
	else 
	{
		$QtdeDias = ($dados['Tipo'] == 'bimestral') ? 60 : 365;
		$Hoje = date('d/m/Y');
		$DiferencaDias = abs(diferencaDatas($dados['Data'],$Hoje));
		$total = abs($DiferencaDias - $QtdeDias);

		// Se a diferença de dias corridos for menor que o mínimo de dias desejáveis, dá erro
		if ($DiferencaDias < $QtdeDias)
		{
			echo '	<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Atenção!</strong> Lembrete não pode ser '.$dados['Status'].'. Aguarde o prazo de '.$total.' dias! </div>';
			exit();
		}

		if ($dados['StatusAtual'] != 'ativo')
		{
			echo '	<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Erro!</strong> Lembrete não pode ser '.$dados['Status'].' novamente! </div>';
			exit();
		}

		$sql = "UPDATE  ".$dados['Tabela']." SET Status = '".$dados['Status']."' WHERE ID = " . $dados['ID'];

		if ($dados['Status'] == 'renovado')
		{
			$sql2 = "INSERT INTO ".$dados['Tabela']." (AdminID,DonoID,Tipo,Data,Status,Descricao,QtdeAvisos) VALUES 
		('".$_SESSION['Empresa']['Dados']['AdminID']."','".$dados['DonoID']."','".$dados['Tipo']."','".converteDataEN(date('d/m/Y'))."','ativo','".$dados['Descricao']."','0')";
		}
	}
}

if ($dados['Tabela'] == 'categorias')
{
	$dados['Canonical'] = canonical($dados['Titulo']);
	$sql = "UPDATE  ".$dados['Tabela']." SET  Titulo =  '".strtoupper($dados['Titulo'])."', Canonical =  '".strtolower($dados['Canonical'])."', Observacao =  '".$dados['Observacao']."' WHERE ID = " . $dados['CategoriaID'];
}

if ($dados['Tabela'] == 'subcategorias')
{
	$dados['Canonical'] = canonical($dados['Titulo']);
	$sql = "UPDATE  ".$dados['Tabela']." SET  Titulo =  '".strtoupper($dados['Titulo'])."', Canonical =  '".strtolower($dados['Canonical'])."', Observacao =  '".$dados['Observacao']."' WHERE ID = " . $dados['SubCategoriaID'];
}

if ($dados['Tabela'] == 'admin')
{
	$sql = "UPDATE  ".$dados['Tabela']." SET  Titulo =  '".strtoupper($dados['Titulo'])."', Login =  '".strtoupper($dados['Login'])."', Senha =  '".senha($dados['Senha'])."', Documento =  '".preg_replace("/[^0-9]/", "", $dados['Documento'])."', Documento2 =  '".preg_replace("/[^0-9]/", "", $dados['Documento2'])."', Telefone =  '".preg_replace("/[^0-9]/", "", $dados['Telefone'])."', Celular =  '".preg_replace("/[^0-9]/", "", $dados['Celular'])."', Email =  '".strtolower($dados['Email'])."' WHERE ID = " . $_SESSION['Empresa']['Dados']['ID'];
}

if ($dados['Tabela'] == 'enderecos')
{
	$dados['Tabela'] = 'admin';
	$sql = "UPDATE  ".$dados['Tabela']." SET  Logradouro =  '".strtoupper($dados['Logradouro'])."', Numero =  '".$dados['Numero']."', Bairro =  '".strtoupper($dados['Bairro'])."', Complemento =  '".strtoupper($dados['Complemento'])."', CEP =  '".preg_replace("/[^0-9]/", "", $dados['CEP'])."', UF =  '".strtoupper($dados['UF'])."', Cidade =  '".strtoupper($dados['Cidade'])."' WHERE ID = " . $_SESSION['Empresa']['Dados']['ID'];
}

$query = $Conexao->prepare($sql);

if ($query->execute())
{
	if (isset($dados['Status']) AND $dados['Status'] == 'renovado')
	{
		$query2 = $Conexao->prepare($sql2);

		if ($query2->execute())
		{
			echo '	<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Parabéns!</strong> Renovado com sucesso! </div>';
		}
		else
		{
			echo '	<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Oops!</strong> Lembrete não pode ser renovado! </div>';
		}
	}
	else
	{
		echo '	<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
			<strong>Parabéns!</strong> Editado com sucesso! </div>';
	}

	if(isset($_REQUEST['AtualizarLembrete']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editLembrete&ID=".$_REQUEST['LembreteID']."&DonoID=".$_REQUEST['DonoID']."&Titulo=".$_REQUEST['Titulo']."'>";
	}
	if(isset($_REQUEST['AtualizarCliente']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editCliente&ID=".$_GET['ClienteID']."'>";
	}
	if(isset($_REQUEST['AtualizarCategoria']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editCategoria&ID=".$_GET['CategoriaID']."'>";
	}
	if(isset($_REQUEST['AtualizarSubCategoria']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editSubCategoria&ID=".$_REQUEST['SubCategoriaID']."&CategoriaTitulo=".$_REQUEST['CategoriaTitulo']."&CategoriaID=".$_REQUEST['CategoriaID']."'>";
	}
	if(isset($_REQUEST['AtualizarEmpresa']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editEmpresa'>";
	}
	if(isset($_REQUEST['AtualizarFornecedor']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=editFornecedor&ID=".$_GET['ClienteID']."'>";
	}
	if( isset($_REQUEST['Lembretes']) AND ($_REQUEST['Lembretes'] == 'sim') )
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=lembretes&ID=".$_REQUEST['DonoID']."&Titulo=".$_REQUEST['Titulo']."'>";
	}
}
else
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao editar. </div>';
}

?>