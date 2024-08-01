<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Configuraciones<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h3>Configuraciones</h3>
            </div>
        </div>
        <div class="row">
            <main class="col s12">
                <table id="table_configuraciones" class="responsive-table striped highlight">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DATOS</th>
                            <th>PUBLICADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DATOS</th>
                            <th>PUBLICADO</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </main>
        </div>
    </div>

</div>
<?php $this->endSection('contenido'); ?>