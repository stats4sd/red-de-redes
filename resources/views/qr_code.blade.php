@extends('layouts.app')

@section('content')
	<div class="container">
		<section class="content mb-5" id="generate_qr_code">
			<h3 class="mb-5"><b>Generar códigos QR</b></h3>
			<p>Use esta página para generar códigos QR para asignar a parcelas específicas.</p>
			<p>Haga clic en el botón de abajo para generar una hoja de códigos que puede imprimir. Debe generar suficientes códigos QR para todas las parcelas que desea registrar en la plataforma. Cada código será único dentro del sistema. Simplemente genere e imprima tantas hojas como necesite para su trabajo.</p>
		</section>
		<div class="visible-print">

		</div>
		<div class="card card-primary">
			<div class="card-body">

				<form method="post" target="_blank" action="{{ route('qr-newcodes') }}">
					@csrf
					<div class="form-group row {{ $errors->has('prefix') ? 'has-error' : '' }}">
						<label for="prefix" class="col-sm-4">Ingrese el prefijo a usar para los códigos</label>
						<div class="col-sm-4">
						<input type="text" class="form-control" id="prefix" name="prefix" onkeyup="standardCode()">
                        <span class="text-danger">{{ $errors->first('prefix') }}</span>
						</div>
					</div>
					<div class="form-group row {{ $errors->has('start_number') ? 'has-error' : '' }}">

						<label for="start_number" class="col-sm-4">¿Desde qué número quieres iniciar?</label>
						<div class="col-sm-4">
							<input type="number" class="form-control" id="start_number" name="start_number">
							<small id="passwordHelpBlock" class="form-text text-muted">Opcional</small>
							<span class="text-danger">{{ $errors->first('start_number') }}</span>

						</div>
					</div>
					<div class="form-group row {{ $errors->has('code_number') ? 'has-error' : '' }}">

						<label for="code_number" class="col-sm-4">¿Cuántos códigos necesitas?</label>
						<div class="col-sm-4">
							<input type="number" class="form-control" id="code_number" name="code_number">
							<small id="passwordHelpBlock" class="form-text text-muted">Los códigos QR se dividirán en varias hojas si es necesario</small>
                            <span class="text-danger">{{ $errors->first('code_number') }}</span>

						</div>
					</div>
					<div class="form-group row {{ $errors->has('suffix') ? 'has-error' : '' }}">
						<label for="suffix" class="col-sm-4">Ingrese el sufijo a usar para los códigos</label>
						<div class="col-sm-4">
						<input type="text" class="form-control" id="suffix" name="suffix" onkeyup="standardCode()">
						<small id="passwordHelpBlock" class="form-text text-muted">Opcional</small>
                        <span class="text-danger">{{ $errors->first('suffix') }}</span>
						</div>
					</div>
					<div class="form-group row {{ $errors->has('code_size') ? 'has-error' : '' }}">
						<label for="sheetSize" class="col-sm-4">Seleccione el tamaño del código</label>
						<div class="col-sm-6">
						<div class="form-check-inline">
						  <label class="form-check-label">
						    <input type="radio" class="form-check-input" value=50 name="code_size" selected>Pequeño
						  </label>
						</div>
						<div class="form-check-inline">
						  <label class="form-check-label">
						    <input type="radio" class="form-check-input" value=21 name="code_size" selected>Mediano
						  </label>
						</div>
						<div class="form-check-inline">
						  <label class="form-check-label">
						    <input type="radio" class="form-check-input" value=10 name="code_size" selected>Grande
						  </label>
						</div>

                        <span class="text-danger">{{ $errors->first('code_size') }}</span>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-4 offset-4">
							<button type="submit" class="btn btn-dark"><b>GENERAR HOJA DE CÓDIGOS PARA IMPRIMIR</b></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
<script type="text/javascript">

	function standardCode() {
  		var qrChar = document.getElementById("qrChar").value;
  		qrChar = qrChar.toUpperCase();
		document.getElementById("qrChar").value=qrChar;
	}
</script>