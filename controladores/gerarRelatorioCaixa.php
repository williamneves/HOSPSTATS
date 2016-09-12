<?php
require("config.php");
require_once("functions.php");

session_start();

header('Content-Type: text/html; charset=UTF-8');

ob_start();
set_time_limit(60);

$Conexao = Conexao::getInstance();

switch ($_REQUEST['Lancamento'])
{
	case 'entrada': $Lancamento = "AND Lancamento = 'entrada'"; 	$apelido = 'Recebimentos'; break;
	case 'saida': $Lancamento = "AND Lancamento = 'saida'"; 		$apelido = 'Despesas'; break;
	
	default: $Lancamento = ""; 						$apelido = 'Recebimentos e Despesas'; break;
}

$SQL = "SELECT * FROM caixa WHERE (Data BETWEEN '".converteDateTimeEN($_REQUEST['DataI'])."' AND '".converteDateTimeEN($_REQUEST['DataF'])."') ".$Lancamento." ORDER by Data ASC";

$dados = $Conexao->query($SQL);

$dados->execute();
$lista = $dados->fetchAll(PDO::FETCH_ASSOC);

echo '<table width="0" border="1" cellspacing="2" cellpadding="2">';

echo '	<tr>
		<td colspan="4" align="center"><strong>'.$_SESSION['Empresa']['Dados']['Titulo'].'</strong></td>	
	</tr>';

$DataInicial = converteDataEN($_REQUEST['DataI']);
$DataFinal = converteDataEN($_REQUEST['DataF']);

if ($DataInicial == $DataFinal)
{
	echo '	<tr>
			<td colspan="4" align="center"><strong>'.$apelido.' de '.converteData($DataInicial).'</strong></td>	
		</tr>';
}
else
{
	echo '	<tr>
			<td colspan="4" align="center"><strong>'.$apelido.' de '.converteData($DataInicial).' a '.converteData($DataFinal).'</strong></td>
		</tr>';
}

echo '	<tr>
		<td align="center"><strong>Data</strong></td>
		<td align="center"><strong>Descri&ccedil;&atilde;o</strong></td>
		<td align="center"><strong>Tipo</strong></td>
		<td align="center"><strong>Valor</strong></td>
	</tr>';

$total = 0;

foreach ($lista as $key => $dado)
{
	$total += $dado['Valor'];

	echo '	<tr>
			<td align="center">'.converteData($dado['Data']).'</td>
			<td align="center">'.utf8_decode($dado['Titulo']).'</td>
			<td align="center">'.utf8_decode($dado['Tipo']).'</td>
			<td align="right">R$ '.$dado['Valor'].'</td>
		</tr>';
}

echo '	<tr>
		<td colspan="3" align="right">Total: </td>	
		<td colspan="1" align="center"><strong>R$ '.number_format($total, 2, ',', '.').'</strong></td>	
	</tr>';

echo '</table>';

// Escolher o formato do arquivo
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=relatorio-".$_REQUEST['Lancamento'].".xls");
header("Pragma: no-cache");
header("Expires: 0");

$resultado = ob_get_contents();

ob_clean();

echo $resultado;

?>