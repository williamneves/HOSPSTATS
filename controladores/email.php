<?php

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false)
{
	echo '<br/><div class="alert alert-warning" role="alert">E-mail inválido!</div>';
}
else
{
	$dados = $_POST;

	require("PHPMailer/class.phpmailer.php");

	//Inicia a classe PHPMailer
	$mail = new PHPMailer();

	//Define os dados do servidor e tipo de conexão
	$mail->IsSMTP(); // Define que a mensagem será SMTP
	$mail->SMTPSecure 	= "tls";
	// $mail->SMTPDebug = 1;
	$mail->Port       	= 587;
	$mail->Host 		= "smtp.sgsmontagemindustrial.com.br"; // Endereço do servidor SMTP
	$mail->SMTPAuth 	= true; // Autenticação
	$mail->Username 	= 'sandro@sgsmontagemindustrial.com.br'; // Usuário do servidor SMTP
	$mail->Password 	= 'SGSMont4g3m'; // Senha da caixa postal utilizada

	//Define o remetente
	$mail->From = 'sandro@sgsmontagemindustrial.com.br'; 
	$mail->FromName = $dados['nome'];

	//Define os destinatário(s)
	$mail->AddAddress('sandro@sgsmontagemindustrial.com.br', 'Contato SGS');

	//Define os dados técnicos da Mensagem
	$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
	$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)

	//Texto e Assunto
	$mail->Subject  = "Mensagem do site: ".$dados['nome']." - ".$dados['email'];

	$mensagemHTML = <<<HTML
	<p>Pessoal, recebemos uma nova mensagem pelo site:</p>
	<p>Nome: {$_POST['nome']}</p>
	<p>E-mail: {$_POST['email']}</p>
	<p>Mensagem: {$_POST['mensagem']}</p>
	<p>Atenciosamente, Equipe sgsmontagemindustrial.com.br</p>
HTML;

	$mail->Body = $mensagemHTML;

	//Envio da Mensagem
	$enviado = $mail->Send();

	//Limpa os destinatários e os anexos
	$mail->ClearAllRecipients();

	if($enviado)
		echo '<br/><div class="alert alert-success" role="alert">Mensagem enviada com sucesso!</div>';
	else
		echo '<br/><div class="alert alert-danger" role="alert">Mensagem não enviada!</div>';
}

?>