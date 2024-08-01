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

            <h1 class="header center orange-text">Bienvenid@ <?php echo $nombreDelUser; ?></h1>
            <?php if(isset($this->session->name_group) && strlen($this->session->name_group)>0) : ?>
                <h4 class="header center orange-text">(<?php echo $this->session->name_group; ?>)</h4>
            <?php endif; ?>

            <div class="row center">
                <a href="#!" id="download-button" class="btn-large waves-effect waves-light orange">Get Started</a>
            </div>
            <br><br>
        </div>
    </div>
<?php $this->endSection('contenido'); ?>
