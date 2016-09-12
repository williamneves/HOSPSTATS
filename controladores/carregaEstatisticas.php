<?php

// include de banco de dados
include_once("config.php");
include_once("functions.php");

$date = explode(" - ", $_REQUEST['daterange']);
$data['Inicial'] = $date[0].' 00:00:00';
$data['Final'] = $date[1].' 23:59:59';

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
				echo banco($sql);
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
				$sql = "SELECT COUNT(*) FROM arqatend WHERE ARQATEND.TIPOATEND IN ('I') AND arqatend.censo IN ('S') AND arqatend.datatend BETWEEN '".$data['Inicial']."' AND '".$data['Final']."'";
				echo banco($sql);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Número de Internações</div>
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
				echo banco($sql);
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
				echo banco($sql);
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
				echo banco($sql);
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
				echo banco($sql);
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
				echo banco($sql);
				?>
			</span>
		</h5>
		<div class="small text-overflow text-muted">Atendimentos Radiologia</div>
	</div>
</div>