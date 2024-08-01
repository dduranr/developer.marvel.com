<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Heroes<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h3>Heroes</h3>
            </div>
        </div>
        <div class="row">
            <main class="col s12">
                <table id="table_heroes" class="responsive-table striped highlight">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID_MARVEL</th>
                            <th>NAME</th>
                            <th>IMAGEN</th>
                            <th>THUMBNAIL_EXTENSION</th>
                            <th>ORIGEN</th>
                            <th>PUBLICADO</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>ID_MARVEL</th>
                            <th>NAME</th>
                            <th>IMAGEN</th>
                            <th>THUMBNAIL_EXTENSION</th>
                            <th>ORIGEN</th>
                            <th>PUBLICADO</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

            </main>
        </div>
    </div>

</div>
<?php $this->endSection('contenido'); ?>