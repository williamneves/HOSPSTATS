<?php

require("security.php");
require("config.php");
require("functions.php");

if( isset($_REQUEST['Clientes']) AND ($_REQUEST['Clientes'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover cliente. </div>';
	exit();
}

if( isset($_REQUEST['subCategoria']) AND ($_REQUEST['subCategoria'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover sub-categoria. </div>';
	exit();
}

if( isset($_REQUEST['Caixa']) AND ($_REQUEST['Caixa'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover lançamento. </div>';
	exit();
}

if( isset($_REQUEST['Categoria']) AND ($_REQUEST['Categoria'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover categoria. </div>';
	exit();
}

if( isset($_REQUEST['Estoque']) AND ($_REQUEST['Estoque'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover produto ou serviço. </div>';
	exit();
}

if( isset($_REQUEST['Lembretes']) AND ($_REQUEST['Lembretes'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover lembrete. </div>';
	exit();
}

if( isset($_REQUEST['Fornecedores']) AND ($_REQUEST['Fornecedores'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover fornecedor. </div>';
	exit();
}

if( isset($_REQUEST['Variavel']) AND ($_REQUEST['Variavel'] == 'nao') )
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover. </div>';
	exit();
}

$Tabela = $_REQUEST['Tabela'];
$ID = $_REQUEST['ID'];
$Tipo = $_REQUEST['Tipo'];

// Para apagar pasta, caso necessário
if(isset($_REQUEST['RemoverDiretorio']))
{
	$diretorio = "../../images/".$_REQUEST['RemoverDiretorio']."/".$ID."/";
	removerTudo($diretorio);
}

$sql = "DELETE FROM ".$Tabela." WHERE ID = :ID";

$query = $Conexao->prepare($sql);
$query->bindParam(":ID", $ID, \PDO::PARAM_INT);

if ($query->execute())
{
	echo '	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Parabéns!</strong> Removido com sucesso! </div>';

	if(isset($_REQUEST['Enderecos']))
	{
		($Tipo == 1) ? $page = 'editCliente' : $page = 'editFornecedor';
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=".$page."&ID=".$_GET['ClienteID']."'>";
	}

	if(isset($_REQUEST['Clientes']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=clientes'>";
	}
	if(isset($_REQUEST['Caixa']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=caixa'>";
	}
	if(isset($_REQUEST['subCategoria']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=subCategorias&ID=".$_REQUEST['CategoriaID']."&Categoria=".$_REQUEST['CategoriaTitulo']."'>";
	}
	if(isset($_REQUEST['Estoque']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=estoque'>";
	}
	if(isset($_REQUEST['Categoria']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=categorias'>";
	}
	if(isset($_REQUEST['Lembretes']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=lembretes&ID=".$_REQUEST['DonoID']."&Titulo=".$_REQUEST['Titulo']."'>";
	}
	if(isset($_REQUEST['Fornecedores']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=fornecedores'>";
	}
	if(isset($_REQUEST['Variavel']))
	{
		if ($Tipo == 11)
		{
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=variaveis'>";
		}
		else
		{
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=variaveis&Tipo=".$Tipo."&Titulo=".$_REQUEST['Titulo']."'>";
		}
	}
}
else
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover. </div>';
}

if(isset($_REQUEST['Arquivo']))
{
	if(isset($_REQUEST['Curso']))
	{
		$file = "../../images/cursos/".$Curso."/fotos/".$_REQUEST['Arquivo'];
	}
	else if(isset($_REQUEST['Evento']))
	{
		$file = "../../images/eventos/".$Evento."/fotos/".$_REQUEST['Arquivo'];
	}
	else
	{
		$file = "../../images/".$Tabela."/".$_REQUEST['Arquivo'];
	}

	if (unlink($file))
	{
		echo '	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Parabéns!</strong> '.$_REQUEST['Arquivo'].' removido com sucesso! </div>';
	}
	else
	{
		echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Falhou ao remover '.$_REQUEST['Arquivo'].'. </div>';
	}
	if(isset($_REQUEST['Curso']))
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=painel.php?pagina=curso_detalhe&ID=".$Curso."'>";
	}
}

?>