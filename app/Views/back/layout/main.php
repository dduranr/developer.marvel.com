<!doctype html>
<html lang="es">
    <head>
        <?php
            #$this->session = \Config\Services::session();
            #$session = \Config\Services::session();
            echo $this->include('back/layout/head');
            echo $this->renderSection('headscripts');
            helper('general');
            $router = \Config\Services::router();
            $metodoActual = $router->methodName();
            $metodoActual = slugify($metodoActual, '_');
            $controladorActual = $router->controllerName();
            $controladorActual = slugify($controladorActual, '_');
            $bodyClasses = strtolower($controladorActual).' '.strtolower($metodoActual);
        ?>
    </head>
    <body class="backend <?php echo $bodyClasses; ?>">
        <?php
            if (isset($_SESSION['logueado'])) echo $this->include('back/layout/header');
            echo $this->include('flashalert/flash_alert');
            echo $this->renderSection('contenido');
            echo $this->include('back/layout/footer');
            echo $this->include('back/layout/footer-scripts');
            echo $this->renderSection('footerscripts');
        ?>
    </body>
</html>