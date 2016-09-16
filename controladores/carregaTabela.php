<?php

// include de banco de dados
include_once("config.php");
include_once("functions.php");

// verificando infos necessárias
if( isset($_REQUEST['tipoExame']) AND isset($_REQUEST['horario']) AND isset($_REQUEST['lib']) AND isset($_REQUEST['nlib']))
{
	$tipoExame = $_REQUEST['tipoExame'];
	$horario = $_REQUEST['horario'];
	$lib = $_REQUEST['lib'];
	$nlib = $_REQUEST['nlib'];
}
else
{
	require_once('sair.php');
}

// Consultar banco com as informãções do form
$SQL = "SELECT DISTINCT I.NUMREQSERV, P.NOMEPAC, T.DESCINTSV, A.CODPLACO, CASE R.LIBERADO WHEN 'S' THEN 'LIBERADO' ELSE 'N' END AS LIBERADO, C.DATASOL,
CASE T.GRUPOINTSV WHEN '1001' THEN 'BIOQUIMICA' WHEN '1002' THEN 'HEMATOLOGIA' WHEN '1003' THEN 'IMUNOLOGIA' WHEN '1004' THEN 'URINALISE' WHEN '1005' THEN 'COPROLOGIA' WHEN '1006' THEN 'ENDOCRINOLOGIA' WHEN '1007' THEN 'MICROBIOLOGIA' WHEN '1008' THEN 'BIOLOGIA MOL' WHEN '1009' THEN 'DIVERSOS' WHEN '1018' THEN 'APOIO' END AS BANCADA
FROM ITMSERV I INNER JOIN TABINTSV T ON I.CODSVSOL = T.CODINTSV
INNER JOIN CABSERV C USING (NUMREQSERV)
INNER JOIN ARQATEND A USING (NUMATEND)
INNER JOIN CADPAC P USING (CODPAC)
LEFT JOIN RESULT R USING (NUMREQSERV)
WHERE C.CODLAB = '03' AND A.TIPOATEND = '".$tipoExame."' AND C.DATASOL >= NOW() - '".$horario." HOUR' :: INTERVAL AND R.LIBERADO IN ('".$lib."','".$nlib."') ORDER BY NUMREQSERV";

// realizando consulta no banco
$consulta = consultaBanco($SQL);

echo '<table id="tabela" width="100%" class="table table-striped table-bordered">';
// echo '<table id="tabela" class="table table-bordered datatable">';

// verificando se existe registros
if(pg_num_rows($consulta) > 0)
{
	// exibindo registros na tabela
	$dados = pg_fetch_all($consulta);

	echo '
	<thead>
		<tr>
			<th>Nº Req.</th>
			<th>Nome do Paciente</th>
			<th>Exame</th>
			<th>Convênio</th>
			<th>Liberado</th>
			<th>Data</th>
			<th>Bancada</th>
		</tr>
	</thead>
	<tbody>';
	foreach ($dados as $key => $dado)
	{
		echo '<tr>';
		echo '<td>'.$dado['numreqserv'].'</td>';
		echo '<td>'.utf8_encode($dado['nomepac']).'</td>';
		echo '<td>'.utf8_encode($dado['descintsv']).'</td>';
		echo '<td>'.$dado['codplaco'].'</td>';
		echo '<td>'.$dado['liberado'].'</td>';
		echo '<td>'.get_time_ago(converteDataTempoTracada($dado['datasol'])).'</td>';
		echo '<td>'.utf8_encode($dado['bancada']).'</td>';
		echo '</tr>';
	}
	echo '</tbody>';
}
else
{
	// exibindo vazio na tabela
	echo '
	<thead>
		<tr>
			<th align="center">Oops...</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="center">Não há registros.</td>
		</tr>
	</tbody>';
}
echo '
	<tfoot>
		<tr>
			<th>Nº Req.</th>
			<th>Nome do Paciente</th>
			<th>Exame</th>
			<th>Convênio</th>
			<th>Liberado</th>
			<th>Data</th>
			<th>Bancada</th>
		</tr>
	</tfoot>';
echo '</table>';

?>

<script type="text/javascript" class="init">

	$(document).ready(function() {
		var table = $('#tabela').DataTable({
			"scrollX": true,
			"columnDefs": [
				{ "visible": false, "targets": 1 }
			],
			"order": [[ 1, 'asc' ]],
			"displayLength": 25,
			"drawCallback": function ( settings ) {
				var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;

				api.column(1, {page:'current'} ).data().each( function ( group, i ) {
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="6">'+group+'</td></tr>'
						);

						last = group;
					}
				} );
			}
		} );

		// Order by the grouping
		$('#tabela tbody').on( 'click', 'tr.group', function () {
			var currentOrder = table.order()[0];
			if ( currentOrder[0] === 1 && currentOrder[1] === 'asc' ) {
				table.order( [ 1, 'desc' ] ).draw();
			}
			else {
				table.order( [ 1, 'asc' ] ).draw();
			}
		} );
	} );

</script>
