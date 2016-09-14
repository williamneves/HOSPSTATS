<?php
	require("controladores/security.php");
	require("controladores/inc.path.php");
	require("controladores/config.php");
	require("controladores/functions.php");

	// Iniciando sessão se não tiver sido iniciada ainda
	if(!session_id())
	{
		session_start();
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
	<meta name="application-name" content="Milestone">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Milestone">
	<meta name="theme-color" content="#4C7FF0">
	<title>Stats - HospitalSC</title>
	<link rel="stylesheet" href="vendor/bower-jvectormap/jquery-jvectormap-1.2.2.css?v=1.<?php echo mt_rand(1,9);?>">
	<link rel="stylesheet" href="styles/app.min.css?v=1.<?php echo mt_rand(1,9);?>">
	<link rel="stylesheet" href="styles/style.css?v=1.<?php echo mt_rand(1,9);?>">
	<?php if ($aba == 'aba_mapas'): ?>
	<link rel="stylesheet" href="vendor/datatables/media/css/dataTables.bootstrap4.css?v=1.<?php echo mt_rand(1,9);?>">
	<?php endif ?>
</head>
<body>
	<div class="app">
		<div class="off-canvas-overlay" data-toggle="sidebar"></div>
		<div class="sidebar-panel">
			<div class="brand">
				<a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen hidden-lg-up">
					<i class="material-icons">menu</i>
				</a>
				<a class="brand-logo">
					<h4>Hopital Saúde Center</h4>
				</a>
			</div>
			<div class="nav-profile dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
					<div class="user-image">
						<img src="images/avatar.jpg" class="avatar img-circle" alt="user" title="user" />
					</div>
					<div class="user-info expanding-hidden"><?=ucfirst(strtolower($_SESSION['Dados']['Login']));?>
						<small class="bold">Adminstrador</small>
					</div>
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#">Perfil</a>
					<a class="dropdown-item" href="#">Configurações</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">Ajuda</a>
					<a class="dropdown-item" href="sair">Sair</a>
				</div>
			</div>
			<nav>
				<p class="nav-title">NAVEGAÇÃO</p>
				<ul class="nav">
					<li>
						<a href="home">
							<i class="material-icons text-primary">home</i>
							<span>Home</span>
						</a>
					</li>
					
					<li>
						<a href="#">
							<span class="menu-caret">
								<i class="material-icons">arrow_drop_down</i>
							</span>
							<i class="material-icons">line_weight</i>
							<span>Mapas</span>
						</a>
						<ul class="sub-menu">
							<?php if ( array_key_exists('all', $_SESSION['Permissoes']) OR (array_key_exists('mapa-farmacia', $_SESSION['Permissoes']) AND $_SESSION['Permissoes']['mapa-farmacia'] == 'allowed') ): ?>
							<li><a href="mapa-farmacia"><span>Farmácia</span></a></li>
							<?php endif ?>
							<?php if ( array_key_exists('all', $_SESSION['Permissoes']) OR (array_key_exists('mapa-laboratorio', $_SESSION['Permissoes']) AND $_SESSION['Permissoes']['mapa-laboratorio'] == 'allowed') ): ?>
							<li><a href="mapa-laboratorio"><span>Laboratório</span></a></li>
							<?php endif ?>
						</ul>
					</li>
					
					<li>
						<a href="#">
							<span class="menu-caret">
								<i class="material-icons">arrow_drop_down</i>
							</span>
							<i class="material-icons text-warning">assessment</i>
							<span>Estatísticas</span>
						</a>
						<ul class="sub-menu">
							<?php if ( array_key_exists('all', $_SESSION['Permissoes']) OR (array_key_exists('estatisticas', $_SESSION['Permissoes']) AND $_SESSION['Permissoes']['estatisticas'] == 'allowed') ): ?>
							<?php endif ?>
							<li><a href="estatisticas"><span>Geral</span></a></li>
							<?php if ( array_key_exists('all', $_SESSION['Permissoes']) OR (array_key_exists('estatisticas-urgencia', $_SESSION['Permissoes']) AND $_SESSION['Permissoes']['estatisticas-urgencia'] == 'allowed') ): ?>
							<li><a href="estatisticas-urgencia"><span>Urgência</span></a></li>
							<?php endif ?>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
		<div class="main-panel">
			<nav class="header navbar">
				<div class="header-inner">
					<div class="navbar-item navbar-spacer-right brand hidden-lg-up">
						<a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen">
							<i class="material-icons">menu</i>
						</a>
						<a class="brand-logo hidden-xs-down">
							<img src="images/logo_white.png" alt="HSC" />
						</a>
					</div>
					<a class="navbar-item navbar-spacer-right navbar-heading hidden-md-down" href="#">
						<span>Painel de Controle</span>
					</a>
					<div class="navbar-search navbar-item">
						<form class="search-form">
							<i class="material-icons">busca</i>
							<input class="form-control" type="text" placeholder="Buscar">
						</form>
					</div>
				</div>
			</nav>
			<div class="main-content">
				
				<!-- Conteúdo -->
				<?php include_once($pagina); ?>
				<!-- /Conteúdo -->

				<div class="content-footer">
					<nav class="footer-left">
						<span>Copyright</span> &copy; <?=date('Y');?> Criado por <a target="_blank" href="http://www.facebook.com/rafaelpereirasouza">Rafael Souza</a>. Colaborações de William Neves e Rodrigo Vallado.
					</nav>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">window.paceOptions = {
		document: true,
		eventLag: true,
		restartOnPushState: true,
		restartOnRequestAfter: true,
		ajax: { trackMethods: [ 'POST','GET'] }
	};</script>
	<script src="scripts/app.min.js"></script>
	<script src="js/cloudflare.min.js"></script>
	<script src="scripts/dashboard/dashboard.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	<?php if ($aba == 'aba_mapas'): ?>
	<script src="vendor/datatables/media/js/jquery.dataTables.js"></script>
	<script src="vendor/datatables/media/js/dataTables.bootstrap4.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('.datatable').DataTable();
		} );
	</script>
	<?php endif ?>
	<?php if ($aba == 'aba_estatisticas'): ?>
	<script type="text/javascript" src="scripts/moment.min.js"></script>
	<script type="text/javascript" src="scripts/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/daterangepicker.css?v=1.<?php echo mt_rand(1,9);?>" />
	<script type="text/javascript">
	$(function() {

		var start = moment();
		var end = moment();

		function cb(start, end) {
			$('#reportrange').val(start.format('Y-MM-DD') + ' - ' + end.format('Y-MM-DD'));
		}

		$('#reportrange').daterangepicker({
			locale: {
				format: 'Y-MM-DD'
			},
			startDate: start,
			endDate: end,
			ranges: {
			   'Hoje': [moment(), moment()],
			   'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 Dias': [moment().subtract(7, 'days'), moment()],
			   '30 Dias': [moment().subtract(30, 'days'), moment()],
			   'Mês atual': [moment().startOf('month'), moment()],
			   'Mês passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(start, end);
		
	});
	</script>
	<?php endif ?>
</body>
</html>
