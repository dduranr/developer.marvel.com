
<div class="container-fluid">
	<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-body p-3" data-bs-theme="dark">
	    <div class="container-fluid">
	        <a class="navbar-brand" href="<?php echo base_url('/'); ?>">
	        	<img class="img-fluid w-25" src="<?php echo base_url('img/logos/logo2.png'); ?>" alt="">
	        </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse" id="navbarSupportedContent">
	            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	                <li class="nav-item">
	                    <a class="nav-link active" aria-current="page" href="<?php echo base_url('/'); ?>">Home</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="<?php echo base_url('/auth/login'); ?>">Iniciar sesión</a>
	                </li>
	            </ul>
                <?php
                    helper('form');
                    $attributes = [
                        'class'   => 'd-flex',
                        'id'      => 'form-buscar-heroes',
                        'csrf_id' => 'csrf',
                        'role'    => 'search',
                        'method'  => 'get'
                    ];
                    echo form_open(base_url('buscar'), $attributes);
                ?>
                    <input id="buscar" name="buscar" class="form-control me-2" type="search" placeholder="¿A quién buscas?" aria-label="Buscar">
                    <button id="buscar_submit" class="btn btn-outline-success py-1" type="submit">Buscar</button>
                </form>
	        </div>
	    </div>
	</nav>
</div>
