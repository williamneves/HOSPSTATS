<div class="content-view">
	<div class="row">
		<form action="javascript:AjaxForm('carregaAqui','formTabela','controladores/carregaTabela.php');" method="GET" id="formTabela">
			<div class="col-lg-6">
				<div class="card card-block">
					<h5 class="card-title">Que tipo de exame deseja consultar?</h5>
					<label class="custom-control custom-radio">
						<input value="urgencia" onclick="javascript:submit()" checked="" id="urgencia" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Urgência</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="internacao" onclick="javascript:submit()" id="internacao" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Internação</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="extConvPart" onclick="javascript:submit()" id="extConvPart" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Externo - Convênio/Particular</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="extSUS" onclick="javascript:submit()" id="extSUS" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Externo - SUS</span>
					</label>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card card-block">
					<h5 class="card-title">Qual período deseja consultar?</h5>
					<label class="custom-control custom-radio">
						<input value="01" onclick="javascript:submit()" checked="" id="1h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">1 hora</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="02" onclick="javascript:submit()" checked="" id="2h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">2 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="06" onclick="javascript:submit()" checked="" id="6h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">6 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="12" onclick="javascript:submit()" id="12h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">12 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="24" onclick="javascript:submit()" id="24h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">24 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="36" onclick="javascript:submit()" id="36h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">36 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="48" onclick="javascript:submit()" id="48h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">48 horas</span>
					</label>
				</div>
			</div>
		</form>
	</div>
	
	<div class="clear"></div>
	<div class="card">
		<div class="card-header no-bg b-a-0">Mapa Laboratório</div>
		<div class="card-block">
			<div id="carregaAqui">
				<p>Escolha uma opção acima para começar!</p>
			</div>
		</div>
	</div>
</div>