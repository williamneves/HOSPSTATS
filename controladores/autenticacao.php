<?php

if(!session_id())
{
	session_start();
}

// Recebendo dados do POST
$dados = $_REQUEST;

if (!$dados['Login'] or !$dados['Senha'])
{
	if (!$dados['Login'])
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Usuário é obrigatório!</div>';
	else
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Senha é obrigatório!</div>';
}
else
{
	//inciando sessão
	ob_start();

	//Incluir arquivos de configuração e banco pgsql
	include_once("config.php");

	$consultarIP = "{".$_SERVER['REMOTE_ADDR']."}";

	// verificacao de ip na lista negra
	$sql = "SELECT * FROM listanegra WHERE ip = '" . $consultarIP . "'";
	$consulta = consultaBanco($sql);

	if (pg_num_rows($consulta) > 0)
	{
		$dados = pg_fetch_assoc($consulta);

		$sql = strtotime($dados['tempo']);
		$banco = strtotime("+1 day", $sql);
		$php = strtotime("now");
		if ($banco < $php)
		{
			$sql = "DELETE FROM listanegra WHERE ip = '" . $consultarIP . "'";
			$consulta = consultaBanco($sql);
			echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Sucesso!</strong> Seu acesso foi liberado!</div>';
		}
		else
			echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Seu acesso foi bloqueado!</div>';
	}
	else
	{
		// Criptografando a senha
		$dados['Login'] = strtoupper($dados['Login']);
		$dados['Senha'] = strtoupper($dados['Senha']);
		$senhaAberta = $dados['Senha'];
		$pass = senha($dados['Senha']);

		// Verificando se usuário existe
		$sql = "SELECT codope FROM cadope WHERE nomeope = '" . $dados['Login'] . "'";
		$consulta = consultaBanco($sql);

		// se existir usuário no banco de dados
		if (pg_num_rows($consulta) > 0)
		{
			// verifico se usuário e senha batem com o informado
			$sql = "SELECT * FROM cadope WHERE nomeope = '" . $dados['Login'] . "' AND senha = '" . $dados['Senha'] . "'";
			$consulta = consultaBanco($sql);

			// se a senha bater com a senha cadastrada no banco
			if (pg_num_rows($consulta) > 0)
			{
				// Retendo os dados do usuário do banco de dados
				$dados = pg_fetch_assoc($consulta);

				// Lendo Json para incrementar a $_SESSION com as permissões
				$info = file_get_contents("../data/acesso.json");
				$permissoes = json_decode($info, true);

				// if (array_key_exists('MAGDA', $permissoes['permissoes']))
				if (array_key_exists($dados['nomeope'], $permissoes['permissoes']))
				{
					foreach ($permissoes['permissoes'][$dados['nomeope']] as $chave => $valor)
					{
						// Crio as permissões dele na Sessão
						$_SESSION['Permissoes'][$chave] = $valor;
					}
				}
				else
				{
					// Usuário precisa ser cadastro em /data/acesso.json
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Você precisa cadastrar pelo menos 1 privilégio para o usuário.</div>'; exit();
				}

				// Criando Sessão com dados do usuário
				$_SESSION['Dados']['ID'] = $dados['codope'];
				$_SESSION['Dados']['Login'] = $dados['nomeope'];
				$_SESSION['Dados']['Responsavel'] = $dados['username'];
				$_SESSION['Dados']['Senha'] = $dados['senha'];
				$_SESSION['Dados']['SenhaAberta'] = $senhaAberta;
				$_SESSION['Dados']['Titulo'] = $dados['username'];

				// verifica se a opcao de auto login foi marcada e se o cookie do mesmo nao existe (Apenas se vier por POST)
				if ($_SERVER['REQUEST_METHOD'] == "POST")
				{
					if (isset($_POST['Auto']) and !isset($_COOKIE['Auto']))
					{
						echo "entrei aqui";
						$tempo = time() + (3600 * 24 * 30);
						setcookie("autoLogin", $dados['Login'] . "&" . $pass, $tempo, "/");
					}
				}

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Parabéns!</strong> Você está conectado.</div>';

				echo '<script type="text/javascript">document.location = "home";</script>';
			}
			else // caso a senha for digitada errada
			{
				// verificando tentativas
				if (isset($_COOKIE['login']))
				{
					if ($_COOKIE['login'] < 4)
					{
						setcookie('login', ($_COOKIE['login'] + 1));

						echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Atenção...</strong> Dados não conferem! Você tem mais ' . (4 - $_COOKIE['login']) . ' tentativas!</div>';
					}
					else if ($_COOKIE['login'] >= 4)
					{
						setcookie("login", "", time() - 3600);

						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Número de tentativas excedido. Volte em 24 horas.</div>';

						$sql = "INSERT INTO listanegra(ip,tempo) VALUES('{" . $_SERVER['REMOTE_ADDR'] . "}',now())";
						$consulta = consultaBanco($sql);
					}
				}
				else
				{
					setcookie('login', 1, time() + 3600);
					echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Atenção...</strong> Confira os dados, por favor!</div>';
				}
			}
		}
		else // se não existir o usuário cadastrado no banco
			echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oops...</strong> Usuário não encontrado!</div>';
	}
	ob_end_flush();
}
?>
