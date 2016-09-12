<?php

if(isset($_REQUEST['pagina']))
{
	@$pagina = $_REQUEST['pagina'];
	switch($pagina)
	{
		case 'home': $pagina = 'destaque.php'; $aba= 'aba_home'; break;

		case 'mapa-laboratorio': $pagina = 'mapa-laboratorio.php'; $aba= 'aba_mapas'; break;
		case 'mapa-farmacia': $pagina = 'mapa-farmacia.php'; $aba= 'aba_mapas'; break;

		case 'estatisticas': $pagina = 'estatisticas.php'; $aba= 'aba_estatisticas'; break;
		case 'estatisticas-urgencia': $pagina = 'estatisticas-urgencia.php'; $aba= 'aba_estatisticas'; break;

		default: $pagina = '404.php'; $aba= 'aba_home'; break;
	}
}
else
{
	$pagina = "destaque.php"; $aba= 'aba_home';
}

?>