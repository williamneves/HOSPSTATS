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


	if ($diffData == 7 OR $diffData == 30 AND $data['MesI'] <> $data['MesF'] )
	{
		
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime("-".($diffData+1)." days", strtotime($data['InicialSemHora'])));
		$data['FinalComparador'] = date('Y-m-d 23:59:59', strtotime("-".($diffData+1)." days", strtotime($data['FinalSemHora'])));
	}
	// Mês atual (Pego o mesmo range do mês passado: se for 01/09 - 12/09, eu pego 01/08 - 12/08)
	else if ($data['InicialSemHora'] == $primeiroDiaMes AND $data['FinalSemHora'] == date('Y-m-d'))
	{
		
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['InicialSemHora'])) . " -1 month"));
		$data['FinalComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['FinalSemHora'])) . " -1 month"));
	}
	// Mês passado (Pego mês passado e comparo com o mês anterior: ex: estou em setembro, pego agosto e comparo com julho)
	else if($data['MesI'].$data['AnoI'] == $data['MesF'].$data['AnoF'] AND $data['DiaI'] == '01' AND (date('m') - $data['MesI'] == 1 OR date('m') - $data['MesI'] == -11) )
	{
		
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['InicialSemHora'])) . " -1 month"));
		$data['FinalComparador'] = date('Y-m-d 00:00:00', strtotime(date("Y-m-d", strtotime($data['FinalSemHora'])) . " -1 month"));
	}
	else
	{
		
		$data['InicialComparador'] = date('Y-m-d 00:00:00', strtotime("-".$diffData." days", strtotime($data['InicialSemHora'])));
		$data['FinalComparador'] = date('Y-m-d 23:59:59', strtotime("-".$diffData." days", strtotime($data['FinalSemHora'])));
	}
}

?>
<!-- teste -->
<div class="clear"></div>
    <div class="col-sm-12 col-md-12 col-lg-12" style="padding:20px">
            <h6 class="m-b-0 v-align-middle text-overflow">
                <i class="material-icons red600">local_hospital</i> Atendimentos Urgência
            </h6>
    </div>
<div class="clear"></div>

<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('A') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000049') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('A') AND arqatend.censo IN ('S') AND ARQATEND.CODCC IN ('000049') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Atend. na Urg.</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE ARQATEND.TIPOATEND IN ('A') AND ARQATEND.CODCC IN ('000049') AND extract(year from age(cadpac.datanasc)) > '13' AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE ARQATEND.TIPOATEND IN ('A') AND ARQATEND.CODCC IN ('000049') AND extract(year from age(cadpac.datanasc)) > '13' AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Atend. na Urg. ADUL</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE ARQATEND.TIPOATEND IN ('A') AND ARQATEND.CODCC IN ('000049') AND extract(year from age(cadpac.datanasc)) <= '13' AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE ARQATEND.TIPOATEND IN ('A') AND ARQATEND.CODCC IN ('000049') AND extract(year from age(cadpac.datanasc)) <= '13' AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Atend. na Urg. PED</div>
	</div>
</div>

<div class="clear"></div>
<div class="col-sm-12 col-md-12 col-lg-12" style="padding:20px">
		<h6 class="m-b-0 v-align-middle text-overflow">
            <i class="material-icons red600">local_hotel</i> Internações pela Urgência
        </h6>
</div>
<div class="clear"></div>

<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. Conv.</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) > '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) > '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. Conv. ADUL</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) <= '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) <= '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco <> ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. Conv. PED</div>
	</div>
</div>

<div class="clear"></div>
<div class="col-sm-12 col-md-12 col-lg-12" style="padding:20px">
		<h6 class="m-b-0 v-align-middle text-overflow">
            <i class="material-icons red600">local_hotel</i> Internações pela Urgência SUS
        </h6>
</div>
<div class="clear"></div>

<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. SUS</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) > '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) > '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. SUS ADUL</div>
	</div>
</div>
<div class="col-sm-4 col-md-4 col-lg-4">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) <= '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				$sqlComparador = "SELECT COUNT(*) FROM arqatend INNER JOIN cadpac ON cadpac.codpac = arqatend.codpac WHERE extract(year from age(cadpac.datanasc)) <= '13' AND ARQATEND.TIPOATEND IN ('I') AND arqatend.codplaco = ('SIH') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['InicialComparador']."' AND '".$data['FinalComparador']."'";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> '.banco($sql).' </span>| <span style="color:#C9C9C9"><i class="material-icons">timer</i> '.banco($sqlComparador);
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">Nº Int. SUS PED</div>
	</div>
</div>



<div class="clear"></div>
<!-- Legenda -->

<div class="col-sm-12 col-md-12 col-lg-12">
<span style="float: right;padding:20px;">Legenda: <span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">person</i> Dados Atuais | <span style="color:#C9C9C9"><i class="material-icons">timer</i> Dados da época anterior. </span></span></span>
</div>

<div class="clear"></div>

<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq <> 'D' AND cadlei.extra <> 'S'
AND cadlei.codlei NOT IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')
";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">local_hotel</i> '.banco($sql).' </span>';
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">TOT. DE LEITOS LIVRES S/UTI</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq = '*'
AND cadlei.codlei NOT IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">local_hotel</i> '.banco($sql).' </span>';
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">TOT. DE LEITOS OCUP. S/UTI</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq <> 'D' 
AND cadlei.codlei IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">local_hotel</i> '.banco($sql).' </span>';
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">TOT. DE LEITOS UTI</div>
	</div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="card card-block stats-bg">
		<h4 class="m-b-0 v-align-middle text-overflow">
			 
			<span>
				<?php
				$sql = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq = '*' 
AND cadlei.codlei IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')";
				echo '<span style="color: #25B67A;font-weight: bolder;"> <i class="material-icons">local_hotel</i> '.banco($sql).' </span>';
				?>
			</span></span>
		</h3>
		<div class="normal text-overflow text-muted">TOT. DE LEITOS OCUP. UTI</div>
	</div>
</div>

<?php

     $QueryA = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq <> 'D' AND cadlei.extra <> 'S'
AND cadlei.codlei NOT IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')
";
     $QueryB = "SELECT COUNT(*) FROM cadlei WHERE cadlei.tipoatend ='I' AND cadlei.tipobloq = '*'
AND cadlei.codlei NOT IN ('UTA-01','UTA-02','UTA-03','UTA-04','UTA-05','UTA-06','UTA-07','UTA-08','UTA-09','UTA-10')";

echo subtrair($QueryA,$QueryB)
?>

