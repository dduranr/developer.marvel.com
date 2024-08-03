<?php $this->extend('front/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?><?php $this->endSection('titulo'); ?>

<?php $this->section('contenido'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				&nbsp;
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<h1>Héroes</h1>
				<hr>
			</div>
		</div>

        <div class="row">

            <?php
                // echo '<pre>';
                // print_r($heroes);
                // echo '<hr>';
                // var_dump($heroes);
                // echo '</pre>';
                // exit();
            ?>


            <div class="col-md-10 text-center">
                <?php if( count($heroes) > 0 ) : ?>

                    <div class="text-start">
                        <small>Datos proporcionados por Marvel. © 2014 Marvel</small>
                    </div>

                    <?php
                        $numCard = 0;
                        foreach ($heroes as $heroe_key => $heroe) :
                    ?>
                        <?php
                            $numCard++;
                            $cardsXfila = 3; // Agrupamos los elementos de n en n
                            $resto = $heroe_key%$cardsXfila; // Si el resto es 0 significa que la card actual es múltiplo de 3
                        ?>

                        <?php if ($heroe_key===0 || $resto===0) : ?>
                            <div class="card-group tarjeta-<?php echo $numCard; ?> mb-5">
                        <?php endif; ?>

                        <div class="card mx-2 border">
                            <?php if (true) : ?>
                                <?php if (strlen(trim($heroe['imagen']))>0) : ?>
    	                            <?php if (strlen(trim($heroe['resourceURI']))>0) : ?>
    	                                <a href="<?php echo $heroe['resourceURI']; ?>" target="_blank">
    	                                    <img class="card-img-top img-thumbnail" src="<?php echo $heroe['imagen']; ?>" alt="<?php echo $heroe['name']; ?>">
    	                                </a>
    	                            <?php else : ?>
    	                                <img class="card-img-top img-thumbnail" src="<?php echo $heroe['imagen']; ?>" alt="<?php echo $heroe['name']; ?>">
    	                            <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="card-body">
                                <?php
                                    $description = strip_tags($heroe['description']);
                                    $caracteresMaxAmostrar = 100;
                                    $sufijo = (strlen($description)>$caracteresMaxAmostrar) ? '...' : '';
                                ?>

	                            <?php if (strlen(trim($heroe['resourceURI']))>0) : ?>
	                                <a href="<?php echo $heroe['resourceURI']; ?>" target="_blank">
	                                    <h4 class="card-title"><?php echo $heroe['name']; ?></h4>
	                                </a>
	                                <a href="<?php echo $heroe['resourceURI']; ?>" target="_blank">
	                                    <?php echo substr($description, 0, $caracteresMaxAmostrar).$sufijo; ?>
	                                </a>
	                            <?php else : ?>
                                    <h4 class="card-title"><?php echo $heroe['name']; ?></h4>
                                    <?php echo substr($description, 0, $caracteresMaxAmostrar).$sufijo; ?>
	                            <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo $heroe['resourceURI']; ?>" target="_blank">
                                    <small class="text-muted"><?php echo $heroe['created_at']; ?></small>
                                </a>
                            </div>
                        </div>
                        <?php
                            // Si el resto es igual a 3 significa que ya se imprimieron 4 cards; si el resto es 4 se imprimieron 5, etc. Por tanto, si el resto es 3 ó 4 (o lo que dé $cardsXfila-1), o bien, si es la última tarjeta, cerramos el DIV.card-group
                            if (($resto===$cardsXfila-1) || ($numCard===count($heroes))) :
                        ?>
                                </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="mt-5 text-start">
                        <h3>No existe ningún superhéroe en la base de datos.</h3>
                        <p>Impórtalos primero. Para ello inicia sesión <a href="<?php echo base_url('auth/login'); ?>" target="_blank">aquí</a> y luego ve al menú <strong>Héroes > Importar</strong></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-2">
                <h4>Categorías</h4>
				<div class="list-group">
				    <a href="https://materializecss.com" target="_blank" class="list-group-item list-group-item-action active" aria-current="true">
				    	materializecss.com
				    </a>
				    <a href="https://codeigniter.com" target="_blank" class="list-group-item list-group-item-action">codeigniter.com</a>
				    <a href="https://getbootstrap.com" target="_blank" class="list-group-item list-group-item-action">getbootstrap.com</a>
				    <a href="https://datatables.net" target="_blank" class="list-group-item list-group-item-action">datatables.net</a>
				    <a class="list-group-item list-group-item-action disabled" aria-disabled="true">python.org</a>
				</div>
            </div>

            <div class="col-md-12 text-center mb-1">
                <div class="d-flex justify-content-center mt-5">
                	<?php echo $pager->links(); ?>
                </div>
            </div>

        </div>

	</div>

<?php $this->endSection('contenido'); ?>
