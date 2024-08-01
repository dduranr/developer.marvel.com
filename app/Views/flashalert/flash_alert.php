<?php
    $session = \Config\Services::session();
    $item    = $session->getFlashdata('item'); // Para poner el flashdata por defecto
    $success = $session->getFlashdata('success');
    $error   = $session->getFlashdata('error');
    $warning = $session->getFlashdata('warning');

    $router = \Config\Services::router();
    $metodoActual = $router->methodName();
    $controladorActual = $router->controllerName();

    // A continuación distinguimos las clases CSS entre el back y front, ya que usamos Materialize para uno y Bootstrap para el otro
?>

<?php if (!empty($item)) : ?>
    <?php if ( trim($controladorActual) === '\App\Controllers\Front\Frontend' ) : ?>
        <div class="row">
            <div class="col-md-6 m-auto my-3">
                <div class="alert alert-info" role="alert">
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col s12 m12">
                <div class="card-panel teal">
                    <span class="white-text"><?php echo $item; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($success)) : ?>
    <?php if ( trim($controladorActual) === '\App\Controllers\Front\Frontend' ) : ?>
        <div class="row">
            <div class="col-md-6 m-auto my-3">
                <div class="alert alert-success" role="alert">
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col s12 m12">
                <div class="card-panel green">
                    <span class="white-text"><?php echo $success; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($error)) : ?>
    <?php if ( trim($controladorActual) === '\App\Controllers\Front\Frontend' ) : ?>
        <div class="row">
            <div class="col-md-6 m-auto my-3">
                <div class="alert alert-danger" role="alert">
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col s12 m12">
                <div class="card-panel red">
                    <span class="white-text"><?php echo $error; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($warning)) : ?>
    <?php if ( trim($controladorActual) === '\App\Controllers\Front\Frontend' ) : ?>
        <div class="row">
            <div class="col-md-6 m-auto my-3">
                <div class="alert alert-warning" role="alert">
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col s12 m12">
                <div class="card-panel yellow">
                    <span class="white-text"><?php echo $warning; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
