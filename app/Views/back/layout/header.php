<?php
    $aplicacionConfig = config('Aplicacion');

    $session = \Config\Services::session();

    $router = \Config\Services::router();
    $metodoActual = $router->methodName();
    $controladorActual = $router->controllerName();
    $posLastSlashInController = strrpos($controladorActual, '\\');
    $controladorActualName = strtolower(substr($controladorActual, ($posLastSlashInController+1)));
?>

<header class="header">
    <ul id="nav-mobile" class="sidenav">
        <li><a href="#">Menú Mobile</a></li>
    </ul>

    <ul id="dropdown1" class="dropdown-content">
        <a href="<?php echo base_url('backend/users/create'); ?>">Crear</a>
        <a href="<?php echo base_url('backend/users/index'); ?>">Ver/Editar</a>
    </ul>
    <ul id="dropdown2" class="dropdown-content">
        <a href="<?php echo base_url('backend/heroes/import'); ?>" id="importar_heroes">Importar</a>
        <a href="<?php echo base_url('backend/heroes/create'); ?>">Crear</a>
        <a href="<?php echo base_url('backend/heroes/index'); ?>">Ver/Editar</a>
    </ul>

    <ul id="dropdown6" class="dropdown-content">
        <li><a href="#"><?php echo $session->name.' '.$session->apellidop.' '.$session->apellidom; ?></a></li>
        <li class="divider"><hr></li>
        <li class="divider"><hr></li>
        <li class="divider"><hr></li>
        <li><a href="<?php echo base_url('auth/logout'); ?>">Cerrar sesión</a></li>
    </ul>


    <nav class="blue-grey darken-4" style="background-color: #d50010 !important;">
        <div class="nav-wrapper px-2">
            <a href="<?php echo base_url('auth/welcome'); ?>" class="brand-logo white-text">
                <img src="<?php echo base_url('img/logos/logo.png'); ?>" width="90" alt="<?php echo APPNAME; ?>" >
            </a>
            <ul class="right hide-on-med-and-down">

                <li class="<?php echo activeMenuItem('user', $controladorActualName); ?>">
                    <a class="dropdown-trigger white-text" href="#!" data-target="dropdown1">
                        Administradores
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
                <li class="<?php echo activeMenuItem('heroes', $controladorActualName); ?>">
                    <a class="dropdown-trigger white-text" href="#!" data-target="dropdown2">
                        Héroes
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>

                <li class="user_profile">
                    <?php if(is_file(FOLDERUPLOADSUSERS.$session->imgprofile)) : ?>
                        <a class="dropdown-trigger white-text" href="#!" data-target="dropdown6">
                            <img src="<?php echo base_url('uploads/users/'.$session->imgprofile); ?>" width="30" alt="<?php echo $session->name.' '.$session->apellidop; ?>">
                        </a>
                    <?php else : ?>
                        <!-- Comentar al pasar a producción (muestra la imagen de perfil sólo en raíz) -->
                        <a class="dropdown-trigger white-text" href="#!" data-target="dropdown6">
                            <img class="thumb_user" src="<?php echo base_url('uploads/users/'.$session->imgprofile); ?>" width="30" alt="<?php echo $session->name.' '.$session->apellidop; ?>">
                        </a>

                        <!-- Descomentar al pasar a producción -->
                        <?php if(false) : ?>
                            <?php if($_SERVER['HTTP_HOST'] === 'developer-marvel-covalto.local.local') : ?>
                                <a class="dropdown-trigger white-text" href="#!" data-target="dropdown6">
                                    <img src='<?php echo file_get_contents(base_url('img/placeholders/base64/user_profile.txt')); ?>' width="30" alt="<?php echo $session->name.' '.$session->apellidop; ?>"/>
                                </a>
                            <?php else : ?>
                                <small class="red-text">Imposible encontrar la imagen: <?php echo FOLDERUPLOADSUSERS.$session->imgprofile; ?></small>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>

                <li>
                    <a class="btn-floating btn-small pulse" href="<?php echo base_url('backend/configuraciones/index'); ?>">
                        <i class="material-icons white-text">settings</i>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>
