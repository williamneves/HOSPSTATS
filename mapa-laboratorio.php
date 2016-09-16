<div class="content-view">
	<div class="row">
		<form action="javascript:AjaxForm('carregaAqui','formTabela','controladores/carregaTabela.php');" method="GET" id="formTabela">
			<div class="col-lg-6">
				<div class="card card-block">
					<h5 class="card-title">Que tipo de exame deseja consultar?</h5>
					<label class="custom-control custom-radio">
						<input value="A" id="urgencia" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Ambulatório</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="E" id="externo" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Externo</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="I" id="internacao" name="tipoExame" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Internação</span>
					</label>
					<label class="custom-control custom-checkbox">
						<input value="S" checked="0" id="liberado" name="lib" type="checkbox" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Liberado</span>
					</label>
					<label class="custom-control custom-checkbox">
						<input value="N" checked="0" id="naoliberado" name="nlib" type="checkbox" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">Não Liberado</span>
					</label>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card card-block">
					<h5 class="card-title">Qual período deseja consultar?</h5>
					<label class="custom-control custom-radio">
						<input value="01" id="1h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">1 hora</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="02" id="2h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">2 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="06" checked="" id="6h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">6 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="12" id="12h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">12 horas</span>
					</label>
					<label class="custom-control custom-radio">
						<input value="24" id="24h" name="horario" type="radio" class="custom-control-input">
						<span class="custom-control-indicator"></span> <span class="custom-control-description">24 horas</span>
					</label>
				</div>
			</div>
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary btn-lg btn-block m-b-xs"><span>Consultar</span></button>
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
