<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $this->renderSection('titulo'); ?></title>

<link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
<link rel="manifest" href="/img/icons/site.webmanifest">

<!--Css boostrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- fonts google -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
<!--Componentes styles-->
<link rel="stylesheet" href="<?php echo base_url('css/styles.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('css/estilos-layout.css?version=4'); ?>">
<link rel="stylesheet" href="<?php echo base_url('css/estilos.css?version=17'); ?>">

<?php $Aplicacion = config('Aplicacion'); ?>
<script>
	let base_url = '';
	base_url = '<?php echo base_url(); ?>/';
</script>
