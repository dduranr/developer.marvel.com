<?php
    $Aplicacion = config('Aplicacion');
    $router = \Config\Services::router();
    $metodoActual = $router->methodName();
    $controladorActual = $router->controllerName();
    $session = \Config\Services::session();
?>
<script>
    base_url = '';
    if (typeof(base_url) === 'undefined') {
        base_url = '<?php echo base_url(); ?>/';
    }

    // console.log('$metodoActual: <?php echo $metodoActual; ?>');
    // console.log('$controladorActual: <?php echo $controladorActual; ?>');

</script>



<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="<?php echo base_url("js/helpers.js?version=1"); ?>"></script>
