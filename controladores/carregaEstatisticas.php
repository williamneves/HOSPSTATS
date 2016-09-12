<?php

// include de banco de dados
include_once("config.php");
include_once("functions.php");

$date = explode(" - ", $_REQUEST['daterange']);
// Inicial é usada na Query Principal
$data['Inicial'] = $date[0].' 00:00:00';
$data['InicialSemHora'] = date('Y-m-d', strtotime($data['Inicial']));
$data['DiaI'] = date('d', strtotime($data['Inicial']));
$data['MesI'] = date('m', strtotime($data['Inicial']));
$data['AnoI'] = date('Y', strtotime($data['Inicial']));
// Final é usada na Query Principal
$data['Final'] = $date[1].' 23:59:59';
$data['FinalSemHora'] = date('Y-m-d', strtotime($data['Final']));
$data['DiaF'] = date('d', strtotime($data['Final']));
$data['MesF'] = date('m', strtotime($data['Final']));
$data['AnoF'] = date('Y', strtotime($data['Final']));

// Verifico se é hoje ou ontem, pego o mesmo dia de uma semana passada referente
// exemplo: se for segunda, pego a última segunda
if ($data['InicialSemHora'] == $data['FinalSemHora'])
{
	echo "sou hoje ou ontem";
	// Hoje, ontem e qualquer opção que a Data Inicial seja igual a Data Final
	$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime("-1 week", strtotime($data['InicialSemHora'])));
	$data['FinalComparador'] = date('Y-m-d 23:59:59', strtotime("-1 week", strtotime($data['FinalSemHora'])));
}
else
{
	// para calcular Mês atual
	$primeiroDiaMes = date('Y-m').'-01';

	// 7 dias ou 30 dias mesma regra
	$diffData = diferencaDatas(converteData($data['InicialSemHora']),converteData($data['FinalSemHora']));

	echo "Diferença de dias: " . $diffData."<br />";

	if ($diffData == 7 OR $diffData == 30 AND $data['MesI'] <> $data['MesF'] )
	{
		echo "sou 7 ou 30";
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime("-".$diffData." days", strtotime($data['InicialSemHora'])));
		$data['FinalComparador'] = date('Y-m-d 23:59:59', strtotime("-".$diffData." days", strtotime($data['FinalSemHora'])));
	}
	// Mês atual (Pego o mesmo range do mês passado: se for 01/09 - 12/09, eu pego 01/08 - 12/08)
	else if ($data['InicialSemHora'] == $primeiroDiaMes AND $data['FinalSemHora'] == date('Y-m-d'))
	{
		echo "sou mês atual";
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['InicialSemHora'])) . " -1 month"));
		$data['FinalComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['FinalSemHora'])) . " -1 month"));
	}
	// Mês passado (Pego mês passado e comparo com o mês anterior: ex: estou em setembro, pego agosto e comparo com julho)
	else if($data['MesI'].$data['AnoI'] == $data['MesF'].$data['AnoF'] AND $data['DiaI'] == '01' AND (date('m') - $data['MesI'] == 1 OR date('m') - $data['MesI'] == -11) )
	{
		echo "sou mês passado";
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['InicialSemHora'])) . " -1 month"));
		$data['FinalComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['FinalSemHora'])) . " -1 month"));
	}
	else
	{
		echo "sou range ou qualquer outra coisa";
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime("-".$diffData." days", strtotime($data['InicialSemHora'])));
		$data['FinalComparador'] = date('Y-m-d 23:59:59', strtotime("-".$diffData." days", strtotime($data['FinalSemHora'])));
	}
}

echo '<pre>';
print_r($data);
echo '</pre>';

?>

<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('A') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000049') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('A') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000049') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos na Urgência</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Número de Internações Convênios</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Número de Internações SUS</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000088') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000088') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Externo - Convênio</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000087') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000087') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Externo - SUS</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000080') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000080') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Endoscopia</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000079') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000079') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Ultra Som</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block">
		<h5 class="m-b-0 v-align-middle text-overflow">
			<span class="small pull-xs-right tag p-y-0 p-x-xs" style="line-height: 24px; color: #555;">
				<div class="fa-hover col-md-3 col-sm-4"><i class="material-icons" aria-hidden="true">assessment </i></div>
			</span>
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000041') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('E') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000041') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo "Solicitado: ".banco($sql)." | Anterior: ".banco($sqlComparador);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Radiologia</div>
	</div>
</div>
