<?php

// include de banco de dados
include_once("config.php");
include_once("functions.php");

// verificando infos necessárias
if( isset($_REQUEST['tipoExame']) AND isset($_REQUEST['horario']) )
{
	$tipoExame = $_REQUEST['tipoExame'];
	$horario = $_REQUEST['horario'];
}
else
{
	require_once('sair.php');
}

// Consultar banco com as informãções do form
if ($tipoExame == 'A')
{
	$SQL = "SELECT I.NUMREQSERV, P.NOMEPAC, T.DESCINTSV, A.CODPLACO, C.DATASOL,
		CASE T.GRUPOINTSV WHEN '1001' THEN 'BIOQUIMICA, WHEN '1002' THEN 'HEMATOLOGIA' WHEN '1003' THEN 'IMUNOLOGIA' WHEN '1004' THEN 'URINALISE' WHEN '1005' THEN 'COPROLOGIA' WHEN '1006' THEN 'ENDOCRINOLOGIA' WHEN '1007' THEN 'MICROBIOLOGIA' WHEN '1008' THEN 'BIOLOGIA MOL' WHEN '1009' THEN 'DIVERSOS' WHEN '1018' THEN 'APOIO' END AS BANCADA
		FROM ITMSERV I INNER JOIN TABINTSV T ON I.CODSVSOL = T.CODINTSV
		INNER JOIN CABSERV C USING (NUMREQSERV)
		INNER JOIN ARQATEND A USING (NUMATEND)
		INNER JOIN CADPAC P USING (CODPAC)
		WHERE C.CODLAB = '03' AND A.TIPOATEND = '".$tipoExame' AND C.DATASOL >= NOW() - '".$horario." HOUR' :: INTERVAL ORDER BY NUMREQSERV";
}
else if ($tipoExame == 'I')
{
	$SQL = "SELECT I.NUMREQSERV, P.NOMEPAC, T.DESCINTSV, A.CODPLACO, C.DATASOL,
		CASE T.GRUPOINTSV WHEN '1001' THEN 'BIOQUIMICA, WHEN '1002' THEN 'HEMATOLOGIA' WHEN '1003' THEN 'IMUNOLOGIA' WHEN '1004' THEN 'URINALISE' WHEN '1005' THEN 'COPROLOGIA' WHEN '1006' THEN 'ENDOCRINOLOGIA' WHEN '1007' THEN 'MICROBIOLOGIA' WHEN '1008' THEN 'BIOLOGIA MOL' WHEN '1009' THEN 'DIVERSOS' WHEN '1018' THEN 'APOIO' END AS BANCADA
		FROM ITMSERV I INNER JOIN TABINTSV T ON I.CODSVSOL = T.CODINTSV
		INNER JOIN CABSERV C USING (NUMREQSERV)
		INNER JOIN ARQATEND A USING (NUMATEND)
		INNER JOIN CADPAC P USING (CODPAC)
		WHERE C.CODLAB = '03' AND A.TIPOATEND = '".$tipoExame."' AND C.DATASOL >= NOW() - '".$horario." HOUR' :: INTERVAL ORDER BY NUMREQSERV";
}
else if ($tipoExame == 'E')
{
	$SQL = "SELECT I.NUMREQSERV, P.NOMEPAC, T.DESCINTSV, A.CODPLACO, C.DATASOL,
		CASE T.GRUPOINTSV WHEN '1001' THEN 'BIOQUIMICA, WHEN '1002' THEN 'HEMATOLOGIA' WHEN '1003' THEN 'IMUNOLOGIA' WHEN '1004' THEN 'URINALISE' WHEN '1005' THEN 'COPROLOGIA' WHEN '1006' THEN 'ENDOCRINOLOGIA' WHEN '1007' THEN 'MICROBIOLOGIA' WHEN '1008' THEN 'BIOLOGIA MOL' WHEN '1009' THEN 'DIVERSOS' WHEN '1018' THEN 'APOIO' END AS BANCADA
		FROM ITMSERV I INNER JOIN TABINTSV T ON I.CODSVSOL = T.CODINTSV
		INNER JOIN CABSERV C USING (NUMREQSERV)
		INNER JOIN ARQATEND A USING (NUMATEND)
		INNER JOIN CADPAC P USING (CODPAC)
		WHERE C.CODLAB = '03' AND A.TIPOATEND = '".$tipoExame."' AND C.DATASOL >= NOW() - '".$horario." HOUR' :: INTERVAL ORDER BY NUMREQSERV";
}
else if ($tipoExame == 'ES')
{
	$SQL = "SELECT CABSERV.NUMREQSERV, CABSERV.NOMEPAC, TABINTSV.DESCINTSV,
		ARQATEND.CODPLACO, CASE ITMSERV.POSICAO WHEN '1' THEN 'SOL' WHEN '2' THEN 'S/R' WHEN '3' THEN 'S/R' WHEN '4' THEN 'C/R' WHEN '5' THEN 'C/R' WHEN '6' THEN 'CAN' END AS POSICAO, CABSERV.DATASOL, CASE TABINTSV.GRUPOINTSV WHEN '1001' THEN 'BIOQUIMICA' WHEN '1002' THEN 'HEMATOLOGIA' WHEN '1018' THEN 'APOIO' WHEN '1003' THEN 'IMUNOLOGIA' WHEN '1004' THEN 'URINALISE' WHEN '1005' THEN 'COPROLOGIA' WHEN '1007' THEN 'MICROBIOLOGIA' WHEN '1009' THEN 'DIVERSOS' END AS BANCADA FROM CABSERV
		INNER JOIN ARQATEND ON ARQATEND.NUMATEND = CABSERV.NUMATEND
		INNER JOIN ITMSERV ON ITMSERV.NUMREQSERV = CABSERV.NUMREQSERV
		INNER JOIN TABINTSV ON TABINTSV.CODINTSV = ITMSERV.CODSVSOL
		INNER JOIN TABLOV ON TABINTSV.GRUPOINTSV = TABLOV.CODIGOITEM
		WHERE TABLOV.NUMLOV = '44' AND CABSERV.CODLAB='03' AND ARQATEND.TIPOATEND='E'
		AND ARQATEND.CARATER NOT IN ('02', 'UR') AND ARQATEND.CODPLACO='BPA' and date(cabserv.datasol)=date(current_date) AND (extract(hour from current_time)-extract(hour from cabserv.datasol) <='".$horario."') order by tabintsv.grupointsv,cabserv.datasol";
}

// realizando consulta no banco
$consulta = consultaBanco($SQL);

echo '<table id="tabela" width="100%" class="table table-striped table-bordered">';
// echo '<table id="tabela" class="table table-bordered datatable">';

$dados = pg_fetch_all($consulta);

// verificando se existe registros
if(pg_num_rows($consulta) > 0)
{
	// exibindo registros na tabela
	$dados = pg_fetch_all($consulta);

	if ($tipoExame == 'urgencia')
	{
		echo '
		<thead>
			<tr>
				<th>Nº Req.</th>
				<th>Nome do Paciente</th>
				<th>Exame</th>
				<th>Convênio</th>
				<th>Posição</th>
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
			echo '<td>'.utf8_encode($dado['posicao']).'</td>';
			echo '<td>'.get_time_ago(converteDataTempoTracada($dado['datasol'])).'</td>';
			echo '<td>'.utf8_encode($dado['bancada']).'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		
	}
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
			<th>Posição</th>
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
