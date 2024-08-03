<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Bienvenid@<?php $this->endSection('titulo'); ?>

<?php $this->section('contenido'); ?>
    <?php if (!isset($this->session)) $this->session = \Config\Services::session(); ?>
    <div class="section no-pad-bot" id="index-banner">
        <div class="container">
            <br><br>
            <?php
                $nombreDelUser = '';
                if(isset($this->session->heroe_nombre) && strlen($this->session->heroe_nombre)>0) $nombreDelUser = $this->session->heroe_nombre;
                else $nombreDelUser = $this->session->name;
            ?>

            <h1 class="header center orange-text">Hola, Covalto</h1>
            <ul>
                <li>
                    <h5 class="header center orange-text">
                        -> El CRUD que solicitan está aquí en el backend (menú <i>Héroes</i>)
                    </h5>
                </li>
                <li>
                    <h5 class="header center orange-text">
                        -> La paginación nativa de CodeIgniter y el buscador que solicitan, están en el frontend: <a href="<?php echo base_url('/'); ?>" target="_blank"><?php echo base_url('/'); ?></a>
                    </h5>
                </li>
            </ul>

            <div class="row center">
                <a href="#!" id="download-button" class="btn-large waves-effect waves-light orange">Get Started</a>
            </div>
            <br><br>
        </div>
    </div>
<?php $this->endSection('contenido'); ?>
