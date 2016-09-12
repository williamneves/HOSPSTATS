<?php

require("security.php");
require("config.php");
require("functions.php");

$dados = $_REQUEST;

$dados['Arquivo']['Nome'] = $_FILES['Arquivo']['name'];						// Nome do Arquivo completo
$dados['Arquivo']['Tamanho'] = round($_FILES['Arquivo']['size']/1024);				// Tamanho em megabytes
$dados['Arquivo']['Extensao'] = substr(strrchr(strtolower($_FILES['Arquivo']['name']), "."), 1);		// Extensão do Arquivo

$dados['AdminID'] = $_SESSION['Empresa']['Dados']['ID'];
$dados['Tipo'] = strtolower($dados['Tipo']);

// Se for empresa, Dono não recebe um ID, recebe um texto, no caso = nao
if ($dados['Tipo'] == 'empresa')
	$dados['Dono'] = 'nao';

// Pasta onde o Arquivo vai ser salvo
$_UP['pasta'] = $_SERVER['DOCUMENT_ROOT'].'/gerenciador/arquivos/'.$dados['AdminID'].'/'.$dados['Tipo'].'/'.$dados['Dono'].'/arquivos/';

// Tamanho máximo do Arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'tif', 'tiff', 'mp4', 'wmv', 'avi', 'mpeg', 'mpge2', 'mov', 'mp3', 'wma', 'wav', 'midi', 'aac', 'ogg', 'flac', 'docx', 'doc', 'dotx', 'dot', 'rtf', 'txt', 'pdf', 'docm', 'dotm', 'xml', 'dic', 'xls');

$Extensoes['image'] = array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'tif', 'tiff');
$Extensoes['video'] = array('mp4', 'wmv', 'avi', 'mpeg', 'mpge2', 'mov');
$Extensoes['audio'] = array('mp3', 'wma', 'wav', 'midi', 'aac', 'ogg', 'flac');
$Extensoes['doc'] = array('docx', 'doc', 'dotx', 'dot', 'rtf', 'txt', 'pdf', 'docm', 'dotm', 'xml', 'dic', 'xls');

// imagem = jpg, jpeg, bmp, gif, png, tif, tiff
// video  = mp4, wmv, avi, mpeg, mpge2
// audio  = mp3, wma, wav, midi, aac, ogg, flac
// doc    = docx, doc, dotx, dot, rtf, txt, pdf, docm, dotm, xml, dic

// Renomeia o Arquivo? (Se true, o Arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = true;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O Arquivo selecionado é maior do que o permitido.';
$_UP['erros'][2] = 'O Arquivo ultrapassa o limite de tamanho especifiado.';
$_UP['erros'][3] = 'O upload do Arquivo foi feito parcialmente.';
$_UP['erros'][4] = 'Não foi feito o upload do Arquivo.';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($_FILES['Arquivo']['error'] != 0)
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Sucesso!</strong> Não foi possível fazer o upload, erro:' . $_UP['erros'][$_FILES['Arquivo']['error']].' </div>'; exit();
}

// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
if (array_search($dados['Arquivo']['Extensao'], $_UP['extensoes']) === false)
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops..</strong> Por favor, envie arquivos com as seguintes extensões: "jpg", "jpeg", "bmp", "gif", "png", "tif", "tiff", "mp4", "wmv", "avi", "mpeg", "mov", "mpge2", "mp3", "wma", "wav", "midi", "aac", "ogg", "flac", "docx", "doc", "dotx", "dot", "rtf", "txt", "pdf", "docm", "dotm", "xml" ou "dic". </div>'; exit();
}

// Faz a verificação do tamanho do Arquivo
if ($_UP['tamanho'] < $_FILES['Arquivo']['size'])
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Sucesso!</strong> O arquivo enviado é muito grande, envie arquivos de até 2Mb. </div>'; exit();
}

// O Arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
// Primeiro verifica se deve trocar o nome do Arquivo
if ($_UP['renomeia'] == true)
{
	// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
	$nome_final = md5(strtolower($dados['Titulo'].$dados['Tipo'].$dados['Data'])).'.'.$dados['Arquivo']['Extensao'];
}
else
{
	// Mantém o nome original do Arquivo
	$nome_final = $_FILES['Arquivo']['name'];
}

if (in_array($dados['Arquivo']['Extensao'], $Extensoes['image']) === true)
	$dados['TipoDoc'] = 'image';
if (in_array($dados['Arquivo']['Extensao'], $Extensoes['video']) === true)
	$dados['TipoDoc'] = 'video';
if (in_array($dados['Arquivo']['Extensao'], $Extensoes['audio']) === true)
	$dados['TipoDoc'] = 'audio';
if (in_array($dados['Arquivo']['Extensao'], $Extensoes['doc']) === true)
	$dados['TipoDoc'] = 'doc';
  
// Depois verifica se é possível mover o arquivo para a pasta escolhida
if (move_uploaded_file($_FILES['Arquivo']['tmp_name'], $_UP['pasta'].$nome_final))
{
	$caminho = $_UP['pasta'].$nome_final;
	chmod($caminho, 0644); // permissão pasta do evento

	// Montando a SQL do banco
	$SQL = "INSERT INTO arquivos (AdminID,Dono,Tipo,TipoDoc,Titulo,Data,IP,Descricao,Tamanho,Extensao) VALUES ('".$dados['AdminID']."','".$dados['Dono']."','".$dados['Tipo']."','".$dados['TipoDoc']."','".$dados['Titulo']."','".dataBanco($dados['Data'],'EN')."','".pegarIP()."','".$dados['Descricao']."','".$dados['Arquivo']['Tamanho']."','".$dados['Arquivo']['Extensao']."')";

	$query = $Conexao->prepare($SQL);

	if ($query->execute())
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=/gerenciador/painel.php?pagina=arquivos&Tipo=".ucfirst($dados['Tipo'])."&ID=".$dados['Dono']."&Titulo=".$dados['TituloAntigo']."'>";
	}
}
else
{
	echo '	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
		<strong>Oops!</strong> Não foi possível enviar o arquivo, tente novamente!. </div>';
}

?>