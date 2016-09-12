<?php

header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time', 300); // 5 minutos
ini_set('max_input_time', 120);
ini_set('max_input_nesting_level', 60);
ini_set('memory_limit', 200);
set_time_limit(0);

$files = $_FILES['files'];

if (array_sum($files['size']) > 2500000)
{
	echo '<script type="text/javascript">alert("Você não pode adicionar mais de 2MB de fotos por vez, tire algumas fotos e tente novamente!");</script>';
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$_SERVER['HTTP_REFERER']."'>";
	exit();
}

$diretorio = $_SERVER['DOCUMENT_ROOT'] . '/images/'.$_REQUEST['Pasta'].'/'.$_REQUEST['ID'].'/fotos/';

/* formatos de imagem permitidos */
$permitidos = array(".jpg",".JPG",".jpeg",".JPEG",".gif",".GIF",".png",".PNG");

for($i = 0, $c = count($files['name']); $i <= $c; ++ $i)
{
	// Separando informações importantes
	$nome = @$files['name'][$i];
	$size = @$files['size'][$i];
	$tamanho = round($size/1024);
	$extensao = '.'.substr(strrchr($nome, "."), 1);

	/* verifica se a extensão está entre as extensões permitidas */
	if(in_array($extensao,$permitidos))
	{
		//se imagem for até 200kb envia
		if($tamanho < 200)
		{
			// Novo nome da foto
			$nomeFinal = "curso-".$_REQUEST['ID']."-".md5(mt_rand(1000,9999)).$extensao;
			
			// Caminho temporário da imagem
			$temporario = $files['tmp_name'][$i];

			$resultado = '';
			
			// Se tudo correr bem, envia a foto
			if(@$upload = move_uploaded_file($temporario, $diretorio . $nomeFinal))
			{
				$caminho = $diretorio.$nomeFinal;
				if(count($files['name']) > 1)
				{
					$resultado .= "Fotos enviadas com sucesso! <br />";
					chmod($caminho, 0644);
				}
				else
				{
					$resultado .= "Foto enviada com sucesso! <br />";
					chmod($caminho, 0644);
				}
			}
			else
			{
				$resultado .= "Falha ao enviar! <br />";
			}
		}
		else
		{
			$resultado .= "Imagem não pode ser maior do que 200kb <br />";
		}
	}
	else
	{
		$resultado .= "Somente são aceitos arquivos do tipo Imagem. <br />";
	}
}

echo '<script type="text/javascript">alert("'.$resultado.'");</script>';

if ($_REQUEST['Pasta'] == 'eventos')
{
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=/admin/painel.php?pagina=evento_detalhe&ID=".$_REQUEST['ID']."'>";
}
if ($_REQUEST['Pasta'] == 'cursos')
{
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=/admin/painel.php?pagina=curso_detalhe&ID=".$_REQUEST['ID']."'>";
}

?>