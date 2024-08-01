<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Usuarios<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h3>Usuarios</h3>
            </div>
        </div>
        <div class="row">
            <main class="col s12">
                <?php ?>
    
                <table id="table_users" class="responsive-table striped highlight">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
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