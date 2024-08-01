<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Iniciar sesión<?php $this->endSection('titulo'); ?>

<?php $this->section('contenido'); ?>
	<div class="container">
		<div class="section">
			<div class="row">
				<br />
			</div>
			<div class="row">
				<div class="col m5">
					<h1>Bienvenido</h1>
					<p>Inicia sesión para acceder al administrador</p>
				</div>
				<div class="col m7">
					<div class="row">
						<?php
							helper('form');
							$attributes = [
								'class'   => 'col s12',
								'id'      => 'form-crear-sesion',
								'csrf_id' => 'csrf'
							];
							echo form_open(base_url('auth/store'), $attributes);
						?>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">account_circle</i>
									<!-- <input type="text" class="validate" id="user" name="user" value="<?php //echo old('user'); ?>" required> -->
									<input type="text" class="validate" id="user" name="user" value="aspirante_a_developer_covalto" required>
									<label for="user">Usuario</label>
									<span class="red-text">
                                        <?php
                                            $sessionERRuser = session('errores.user');
                                            if (is_array($sessionERRuser)) echo implode(', ', $sessionERRuser);
                                            else echo $sessionERRuser;
                                        ?>
									</span>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">lock</i>
									<!-- <input type="password" class="validate" id="pass" name="pass" value="<?php //echo old('pass'); ?>" required> -->
									<input type="password" class="validate" id="pass" name="pass" value="FawIC60p9MplHV$4#NwkmN6jpgybozL6U" required>
									<label for="pass">Contraseña</label>
									<span class="red-text">
                                        <?php
                                            $sessionERRpass = session('errores.pass');
                                            if (is_array($sessionERRpass)) echo implode(', ', $sessionERRpass);
                                            else echo $sessionERRpass;
                                        ?>
									</span>
								</div>
							</div>
							<div class="row right-align">
								<input type="submit" class="input-submit btn-large waves-effect waves-light white-text blue-grey darken-4" value="Entrar">

								<?php if (GOOGLE_RECAPTCHA_VERSION === 2) : ?>
									<div class="g-recaptcha mt-3 mb-3" data-sitekey="<?php echo GOOGLE_SITEKEY; ?>"></div>
								<?php elseif (GOOGLE_RECAPTCHA_VERSION === 3) : ?>
									<input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response" value="" />
								<?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->endSection('contenido'); ?>
