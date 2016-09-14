<?php
	session_start();
	if(isset($_SESSION['Admin']))
	{
		header("Location: painel.php");
	}
?>

<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximum-scale=1">
<meta name="msapplication-tap-highlight" content="no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Hospital">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Hospital">
<meta name="theme-color" content="#4C7FF0">
<title>Hospital</title>

<script type="text/javascript">
//<![CDATA[
try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:1472648244,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"/cdn-cgi/nexp/dok3v=1613a3a185/"},atok:"118bf2f00a0766461283b691122e433a",petok:"004cbcb0fa567c6d8ce99410301002cc217f831c-1473354336-1800",zone:"nyasha.me",rocket:"0",apps:{"ga_key":{"ua":"UA-50530436-1","ga_bs":"2"}},sha2test:0}];!function(a,b){a=document.createElement("script"),b=document.getElementsByTagName("script")[0],a.async=!0,a.src="//ajax.cloudflare.com/cdn-cgi/nexp/dok3v=0489c402f5/cloudflare.min.js",b.parentNode.insertBefore(a,b)}()}}catch(e){};
//]]>
</script>
<link rel="stylesheet" href="styles/app.min.css">
<link rel="stylesheet" href="styles/loaders.css">

</head>

<body>

<div class="app no-padding no-footer layout-static">
	<div class="session-panel">
		<div class="session">
			<div class="session-content">
				<div class="card card-block form-layout">

					<form action="javascript:AjaxForm('resultado','validate','controladores/autenticacao.php');" id="validate" role="form" name="validate" method="POST">
					<div class="text-xs-center m-b-3">
						<img src="images/hsc.png" height="80" alt="" class="m-b-1" />
						<h5>Bem Vindo!</h5>
						<p class="text-muted">Faça login para continuar</p>
					</div>
					<fieldset class="form-group">
						<label for="username">Usuário: (Use seu usuário na WARELINE)</label>
						<input type="text" name="Login" class="form-control form-control-lg" id="usuario" placeholder="Usuário" required />
					</fieldset>
					<fieldset class="form-group">
						<label for="password">Senha: (Use sua senha na WARELINE)</label>
						<input type="password" name="Senha" class="form-control form-control-lg" id="password" placeholder="******" required />
					</fieldset>
					<label class="custom-control custom-checkbox m-b-1">
					<input type="checkbox" name="Auto" class="custom-control-input">
					<span class="custom-control-indicator"></span> <span class="custom-control-description">Mantenha-me conectado!</span>
					</label><button class="btn btn-primary btn-block btn-lg" type="submit">Login</button>
					<br/>
					<div id="resultado" align="center"></div>
					<br/>
					</form>
				</div>
			</div>
			<footer class="text-xs-center p-y-1">
				<p>Copyright &copy; 2016 Criado por <a target="_blank" href="http://www.facebook.com/rafaelpereirasouza">Rafael Souza</a>. Colaborações de William Neves e Rodrigo Vallado.</p>
			</footer>
		</div>
	</div>
</div>
<script type="text/javascript">window.paceOptions = {
	document: true,
	eventLag: true,
	restartOnPushState: true,
	restartOnRequestAfter: true,
	ajax: {
	  trackMethods: [ 'POST','GET']
	}
  };</script>
<script src="scripts/app.min.js"></script>
<script src="vendor/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>
<script src="scripts/helpers/noty-defaults.js"></script>
<script src="scripts/ui/notifications.js"></script>
<script src="vendor/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript">$('#validate').validate();</script>
<script type="text/javascript" src="js/ajax.js"></script>
</body>
</html>
