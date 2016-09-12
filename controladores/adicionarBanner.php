<?php

require("security.php");
require("config.php");

// Dados que vem do POST
$dados = $_REQUEST;
$foto = $_FILES['destaque'];

// Upload de imagem (Começando a brincadeira :D)

// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = $_SERVER['DOCUMENT_ROOT'] . '/images/banner/';

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('jpg', 'png', 'gif','JPG','.PNG','.GIF');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false; 

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($foto['error'] != 0)
{
	die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$foto['error']]);
	exit; // Para a execução do script
}

// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

// Faz a verificação da extensão do arquivo
$extensao = strtolower(end(explode('.', $foto['name'])));

if (array_search($extensao, $_UP['extensoes']) === false)
{
	echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
}
// Faz a verificação do tamanho do arquivo
else if ($_UP['tamanho'] < $foto['size'])
{
	echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
}
// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
else
{
	// Primeiro verifica se deve trocar o nome do arquivo
	if ($_UP['renomeia'] == true)
	{
		// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
		$nome_final = time().'.jpg';
	}
	else
	{
		// Mantém o nome original do arquivo
		$nome_final = mt_rand(100,999).'_'.$foto['name'];
	}
	// Depois verifica se é possível mover o arquivo para a pasta escolhida
	if (move_uploaded_file($foto['tmp_name'], $_UP['pasta'] . $nome_final))
	{
		// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
		echo "Banner cadastrado com sucesso!";

		header('Location: ../painel.php?pagina=banner');

		$query = $Conexao->prepare("INSERT INTO banner (Titulo,Foto,Descricao,Categoria,URL) VALUES (?,?,?,?,?)");
		$query->bindValue(1,$dados['Titulo'],PDO::PARAM_STR);
		$query->bindValue(2,$nome_final,PDO::PARAM_STR);
		$query->bindValue(3,$dados['Descricao'],PDO::PARAM_STR);
		$query->bindValue(4,$dados['Categoria'],PDO::PARAM_STR);
		$query->bindValue(5,$dados['URL'],PDO::PARAM_STR);
		
		if($query->execute())
		{
			echo "Banner cadastrado com sucesso!";
		}
		else
		{
			echo "Erro ao enviar para o banco!"; 
		}

		$caminho = $_UP['pasta'].$nome_final;

		// Adiciono uma permissão para exibir o arquivo e solucionar a porra do erro que estava dando até agora!
		chmod($caminho, 0777);
	}
	else
	{
		// Não foi possível fazer o upload, provavelmente a pasta está incorreta
		echo "Não foi possível enviar a foto, tente novamente!";
	}
}

?>