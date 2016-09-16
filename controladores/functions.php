<?php

date_default_timezone_set("America/Sao_Paulo");

function converteDataTempoTracada($data)
{
	 return date('d-m-Y H:i:s', strtotime($data));
}

// tempo em horas, minutos, segundos...
function get_time_ago($time_stamp)
{
	$time_stamp = strtotime($time_stamp);

	$time_difference = strtotime('now') - $time_stamp;
 
	if ($time_difference >= 60 * 60 * 24 * 365.242199)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 365.242199 days/year
		 * This means that the time difference is 1 year or more
		 */
		return get_time_ago_string($time_stamp, 60 * 60 * 24 * 365.242199, 'ano');
	}
	elseif ($time_difference >= 60 * 60 * 24 * 30.4368499)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 30.4368499 days/month
		 * This means that the time difference is 1 month or more
		 */
		return get_time_ago_string($time_stamp, 60 * 60 * 24 * 30.4368499, 'mês');
	}
	elseif ($time_difference >= 60 * 60 * 24 * 7)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 7 days/week
		 * This means that the time difference is 1 week or more
		 */
		return get_time_ago_string($time_stamp, 60 * 60 * 24 * 7, 'semana');
	}
	elseif ($time_difference >= 60 * 60 * 24)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day
		 * This means that the time difference is 1 day or more
		 */
		return get_time_ago_string($time_stamp, 60 * 60 * 24, 'dia');
	}
	elseif ($time_difference >= 60 * 60)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour
		 * This means that the time difference is 1 hour or more
		 */
		return get_time_ago_string($time_stamp, 60 * 60, 'hora');
	}
	else
	{
		/*
		 * 60 seconds/minute
		 * This means that the time difference is a matter of minutes
		 */
		return get_time_ago_string($time_stamp, 60, 'minuto');
	}
}
 
function get_time_ago_string($time_stamp, $divisor, $time_unit)
{
	$time_difference = strtotime("now") - $time_stamp;
	$time_units      = floor($time_difference / $divisor);
 
	settype($time_units, 'string');
 
	if ($time_units === '0')
	{
		return 'menos de 1 ' . $time_unit . ' atras';
	}
	elseif ($time_units === '1')
	{
		return '1 ' . $time_unit . ' atras';
	}
	else
	{
		/*
		 * More than "1" $time_unit. This is the "plural" message.
		 */
		// TODO: This pluralizes the time unit, which is done by adding "s" at the end; this will not work for i18n!
		return $time_units . ' ' . $time_unit . 's atras';
	}
}

function dataPT()
{
	$data = date('D');
	$mes = date('M');
	$dia = date('d');
	$ano = date('Y');

	$semana = array(
	'Sun' => 'Domingo', 
	'Mon' => 'Segunda-Feira',
	'Tue' => 'Terca-Feira',
	'Wed' => 'Quarta-Feira',
	'Thu' => 'Quinta-Feira',
	'Fri' => 'Sexta-Feira',
	'Sat' => 'Sábado'
	);

	$mes_extenso = array(
	'Jan' => 'Janeiro',
	'Feb' => 'Fevereiro',
	'Mar' => 'Marco',
	'Apr' => 'Abril',
	'May' => 'Maio',
	'Jun' => 'Junho',
	'Jul' => 'Julho',
	'Aug' => 'Agosto',
	'Nov' => 'Novembro',
	'Sep' => 'Setembro',
	'Oct' => 'Outubro',
	'Dec' => 'Dezembro'
	);

	echo $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
}

function banco($sql)
{
	include_once ("config.php");
	$consulta = consultaBanco($sql);

	// Retendo os dados do usuário do banco de dados
	$dados = pg_fetch_assoc($consulta);

	return $dados['count'];
}

function pegarIP()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
 
}

function geraTimestamp($data)
{
	$partes = explode('/', $data);
	return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function diferencaDatas($dataInicial,$dataFinal)
{
	// Usa a função criada e pega o timestamp das duas datas:
	$time_inicial = geraTimestamp($dataInicial);
	$time_final = geraTimestamp($dataFinal);

	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos

	// Calcula a diferença de dias
	$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

	// Exibe qtde de dias
	return $dias;
}

function converteData($data)
{
	 return date('d/m/Y', strtotime($data));
}

function converteDataTempo($data)
{
	 return date('d/m/Y H:i:s', strtotime($data));
}

function converteDataEN($data)
{
	$data = str_replace("/", "-", $data);
	return date('Y-m-d', strtotime($data));
}

function converteDateTimeEN($data)
{
	$data = str_replace("/", "-", $data);
	return date('Y-m-d H:i:s', strtotime($data));
}

function dataBanco($data, $converte)
{
	$data = str_replace(" 00:00:00", "", $data);


	if ($converte == "BR") {

		return implode("/", array_reverse(explode("-", $data)));
	} else {
		return implode("-", array_reverse(explode("/", $data)));
	}
}

function formata_data($dataa)
{
	$banco =  explode(" ",$dataa);
	$data_pt = implode("/", array_reverse(explode("-",$banco[0])));
	$separa = explode("/",$data_pt);

	// formata o campo mes
	$mesnome['01'] = "Janeiro";
	$mesnome['02'] = "Fevereiro";
	$mesnome['03'] = "Março";
	$mesnome['04'] = "Abril";
	$mesnome['05'] = "Maio";
	$mesnome['06'] = "Junho";
	$mesnome['07'] = "Julho";
	$mesnome['08'] = "Agosto";
	$mesnome['09'] = "Setembro";
	$mesnome[10] = "Outubro";
	$mesnome[11] = "Novembro";
	$mesnome[12] = "Dezembro";

	$horario = explode(":",$banco[1]);

	return array("ano" => $separa[2],"mes" => $separa[1], "mes_nome" => $mesnome[$separa[1]],"dia" => $separa[0],"hora" => $horario[0],"minuto" => $horario[1]);
}





function canonical($string) 
{		
	$lixo = array
	(
		"á" => "a","à" => "a","ã" => "a","â" => "a",
		"Á" => "a","À" => "a","Ã" => "a","Â" => "a",
		"é" => "e","è" => "e","ê" => "e",
		"É" => "e","È" => "e","Ê" => "e",
		"í" => "i","ì" => "i","î" => "i",
		"Í" => "i","Ì" => "i","Î" => "i",
		"ó" => "o","ò" => "o","õ" => "o","ô" => "o",
		"Ó" => "o","Ò" => "o","Õ" => "o","Ô" => "o",
		"ú" => "u","ù" => "u","û" => "u",
		"Ú" => "u","Ù" => "u","Û" => "u",
		"ç" => "c","Ç" => "c",
		"ñ" => "n","Ñ" => "n",
		" " => "-","/" => "-"
	);
	return str_replace(array_keys($lixo), array_values($lixo), $string); 

}

function paginacao($tabela, $limite, $conexao, $pagina, $pg = 1, $where = NULL, $val_WHERE = NULL,$tipo_WHERE = '==', $oder = NULL, $val_ORDER = NULL)
{
	/*
	CHAMANDO A FUNCAO
	
	$tabela = Tabela de onde quer os dados
	$limite = Quantos registros quer por paginas
	$conexao = Passar a vairavel de conexao pra funcao
	$pagina = pagina AJAX que tem o conteudo (Ex: carregarEventos.php)
	$pg = 1
	$where = Campo do Where
	$val_WHERe = Valor que vai compara com o campo
	$tipo_WHERE = Like ou =
	$order = Campo que vai se ordenado
	$val_ORDER = se é DESC ASC RAND
	
	*/
	//iniciando variaveis de controle
	
	$sql_query 			= "";
	$sql_total 			= "";
	$retorno			= array();
	$paginacao_HTML		= "";
	$total_paginas		= 1;
	$retorno['erro']	= 0;
	$retorno['total_resultados'] = 0;
	
	define('ANTERIOR_INICIO','<li>');
	define('ANTERIOR_FIM','</li>');
	
	define('PROXIMO_INICIO','<li>');
	define('PROXIMO_FIM','</li>');
		
	define('ATUAL_INICIO','<li>');
	define('ATUAL_FIM','</li>');

	if(!is_numeric($pg))
	{
		$pg = 1;
	}
	
	$inicio = ($pg * $limite) - $limite;
	
	//inciando a query normal
	$sql_query .= "SELECT * FROM ".$tabela."";
	
	if($where != NULL and $val_WHERE != NULL)
		$sql_query .= " WHERE ".$where." ".$tipo_WHERE." '".$val_WHERE."'";
	
	if($oder != NULL and $val_ORDER != NULL)
		$sql_query .= " ORDER BY ".$oder." ".$val_ORDER;
	
	//SQL para pegar o total de registro do banco
	$sql_total = $sql_query;
	
	//SQL para paginacao
	$sql_query .= " LIMIT ".$inicio.", ".$limite;
	$query = $conexao->query($sql_query);
		
	//Executando a query para procura a quantidade de registro
	$total_reg = $conexao->query($sql_total);
	
	$total = $total_reg->rowCount();
	
	// se a query que esta pesquisando o total de registros nao encontra nenhuma linha retorna 1 para parar a funcao
	if($total < 1)
	{
		$retorno['erro'] = 1;
	}
	else
	{
		
		// retorna o total de linhas encontradas
		$retorno['total_resultados'] = $total;
		
		//retorna o conteudo da busca da query
		$retorno['resultado_query'] = $query;
			
		
		$controle_paginacao = 6;
		
		
		//Calculando pagina anterior
		$menos = $pg - 1;
		// Calculando pagina posterior
		$mais = $pg + 1;
		
		
		$pgs = ceil($total / $limite);
		if($pgs > 1 ) 
		{
			
			if (($pg-$controle_paginacao) < 1 )
			$anterior = 1;
			
			else 
			$anterior = $pg-$controle_paginacao;
			
			if (($pg+$controle_paginacao) > $pgs )
			$posterior = $pgs;
			else
			
			$posterior = $pg + $controle_paginacao;
			
			$var = (isset($_GET['ano'])) ? "&ano=".$_GET['ano'] : "";
			if($menos > 0) 
			{
				$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$menos.$var."');";
				$paginacao_HTML .= ANTERIOR_INICIO.'<a title="Página Anterior" href="'.$link.'">ANTERIOR</a>'.ANTERIOR_FIM;
			}
			
			
			for($i=$anterior;$i <= $posterior; $i++) 
			{
				if($pg == $i)
				{
					$paginacao_HTML .= ATUAL_INICIO."<li title='Página Atual' class='current'>".$i."</li>".ATUAL_FIM; // Escreve somente o número da página sem ação alguma
				}
				else
				{
					$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$i.$var."');";
					$paginacao_HTML .= '<li><a title="Página '.$i.'" href="'.$link.'">'.$i.'</a></li>';
				}
				
			}
			
			if($mais <= $pgs)
			{
				$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$mais.$var."');";
				$paginacao_HTML .= PROXIMO_INICIO.'<a title="Próxima Página" href="'.$link.'">PRÓXIMO</a>'.PROXIMO_FIM;
			}
			
			$retorno['html'] = $paginacao_HTML;
			$retorno['total_paginas'] = $total_paginas;
			
		}
		else
		{
			$retorno['html'] = "";
		}
	}
	
	return $retorno;
}

function encurta($texto,$limite)
{
	if(strlen($texto) > $limite)
	{
		if($texto[$limite] == " ")
		{
			return substr($texto,0,$limite);
		}
		else
		{
			return encurta($texto,($limite+1));
		}
	}
	else
	{
		return $texto;
	}
}

function removerTudo($rootDir)
{
	if (!is_dir($rootDir))
	{
		return false;
	}
	if (!preg_match("/\\/$/", $rootDir))
	{
		$rootDir .= '/';
	}

	$dh = opendir($rootDir);

	while (($file = readdir($dh)) !== false)
	{
		if ($file == '.'  ||  $file == '..')
		{
			continue;
		}
		if (is_dir($rootDir . $file))
		{
			removerTudo($rootDir . $file);
		}
		else if (is_file($rootDir . $file))
		{
			unlink($rootDir . $file);
		}
	}
	closedir($dh);
	rmdir($rootDir);
	return true;
}

?>
