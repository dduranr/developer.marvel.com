<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p id="msgAJAX"></p>
		</div>
	</div>
</div>
<footer class="page-footer blue-grey darken-4">
	<div class="container">
		<div class="row">
			<div class="col s6">
				<h4>Marvel Developer Portal</h4>
				<a href="mailto:official.dduran@gmail.com" class="white-text">official.dduran@gmail.com</a>
				<p>55 5658 1111, Exts. 7401 y 4302</p>
			</div>
			<div class="col s6">
				<p class="white-text">
					<a href="https://maps.app.goo.gl/LjVjKjcoYKinVmdn9" target="_blank" class="white-text">
						Calle Ferrocarril de Cuernavaca 689-Piso 9, Amp Granada,<br>
						 Miguel Hidalgo, 11529 Ciudad de México, CDMX.
					</a>
				</p>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			Hecho por <a class="white-text" href="mailto:official.dduran@gmail.com">official.dduran@gmail.com</a> con CodeIgniter <?php echo \CodeIgniter\CodeIgniter::CI_VERSION; ?>, Materialize para el back y Bootstrap para el front</a>
		</div>
	</div>


	<div id="modal_eliminar" class="modal">
		<div class="modal-content">
			<h5 class="black-text">¿Estás segur@ de eliminar este elemento?</h5>
			<br />
			<span class="nombre badge red badgeCustom1"></span>
			<br />
		</div>
		<div class="modal-footer">
			<a href="#!" id="modal_eliminar_no" class="modal-close waves-effect waves-light btn red"><i class="material-icons left">close</i>No</a>
			<a href="#!" id="modal_eliminar_si" class="modal-close waves-effect waves-light btn green"><i class="material-icons left">done</i>Sí</a>
		</div>
	</div>

</footer>
<div id="spinner-gif" class="spinner-gif">
	<div class="alert alert-success" rol="alert"> 
		<h4>Importando héroes...</h4>
		<p>No cierres esta ventana. Se recargará automáticamente.</p>
	</div>
    <img src="<?php echo base_url('img/preloaders/loading.gif'); ?>" alt="Cargando..." />
</div>