<link rel="stylesheet" href="vendor/jquery.tagsinput/src/jquery.tagsinput.css">
<link rel="stylesheet" href="vendor/intl-tel-input/build/css/intlTelInput.css">
<link rel="stylesheet" href="vendor/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<link rel="stylesheet" href="vendor/clockpicker/dist/bootstrap-clockpicker.min.css">
<link rel="stylesheet" href="vendor/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<link rel="stylesheet" href="vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css">
<link rel="stylesheet" href="vendor/jquery-labelauty/source/jquery-labelauty.css">
<link rel="stylesheet" href="vendor/multiselect/css/multi-select.css">
<link rel="stylesheet" href="vendor/ui-select/dist/select.css">
<link rel="stylesheet" href="vendor/select2/select2.css">
<link rel="stylesheet" href="vendor/selectize/dist/css/selectize.css">
<style media="screen" type="text/css">

.stats-bg:after {content: "\f200 ";font-family: FontAwesome;font-style: normal;font-weight: normal;text-decoration: inherit;position: absolute;font-size: 30px;color: #777;top: 17%;left: 83%;z-index: 1;}

</style>

<div class="content-view">
	<div class="row">
		<form action="javascript:AjaxForm('carregaAqui','formTabela','controladores/carregaEstatisticas-urgencia.php');" method="GET" id="formTabela">
			<div class="col-lg-12">
				<div class="card card-block">
					<h5 class="card-title">Defina um período para realizar a busca</h5>
					<div class="input-prepend input-group m-b-1"><span class="add-on input-group-addon"><i class="material-icons">date_range </i></span><input id="reportrange" class="form-control drp" type="text" name="daterange" /></div>
				</div>
			</div>
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary btn-lg btn-block m-b-xs"><span>Consultar</span></button>
			</div>
		</form>
	</div>
	<div class="clear"></div>
	<div class="row">
		<div id="carregaAqui"></div>
	</div>
</div>