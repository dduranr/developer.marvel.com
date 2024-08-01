<!doctype html>
<html lang="es">
	<head>
		<?php
			echo $this->include('front/layout/head');
			helper('general');
			$router = \Config\Services::router();
			$metodoActual = $router->methodName();
			$metodoActual = slugify($metodoActual, '_');
			$controladorActual = $router->controllerName();
			$controladorActual = slugify($controladorActual, '_');
			$bodyClasses = strtolower($controladorActual).' '.strtolower($metodoActual);
		?>
	</head>
	<body class="frontend <?php echo $bodyClasses; ?>">
		<?php echo $this->include('front/layout/header'); ?>
		<?php echo $this->include('flashalert/flash_alert'); ?>
		<?php echo $this->renderSection('contenido'); ?>
		<?php echo $this->include('front/layout/footer'); ?>
		<?php echo $this->include('front/layout/footer-scripts'); ?>
		<?php echo $this->renderSection('footer-scripts'); ?>
	</body>
</html>