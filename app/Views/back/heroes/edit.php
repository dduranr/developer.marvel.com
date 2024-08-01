<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Editar heroe<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col m12">
                    <h3>Editando la héroe <span class="bbottom-1"><i><?php echo $heroe['name']; ?></i></span></h3>
                </div>
            </div>
            <div class="row">
                <div class="col m12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <?php
                    helper('form');
                    $attributes = [
                        'class'   => 'col m12',
                        'id'      => 'form-editar-heroe',
                        'csrf_id' => 'csrf'
                    ];
                    echo form_open_multipart(base_url('backend/heroes/update/'.$heroe['id']), $attributes);
                ?>
                    <div class="row">
                        <div class="input-field col m6">
                            <i class="material-icons prefix">person</i>
                            <input type="number" class="validate" value="<?php echo $heroe['id_marvel']; ?>" disabled>
                            <label for="icon_prefix">Id Marvel</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <input type="text" class="validate" id="name" name="name" value="<?php echo $heroe['name']; ?>" required>
                            <label for="name">Nombre</label>
                            <span class="red-text">
                                <?php
                                    $sessionERRname = session('errores.name');
                                    if (is_array($sessionERRname)) echo implode(', ', $sessionERRname);
                                    else echo $sessionERRname;
                                ?>
                            </span>
                        </div>
                        <div class="input-field col m12">
                            <p>&nbsp;</p>
                            <i class="material-icons prefix">format_align_left</i>
                            <textarea class="tinymce_full" name="description" id="description"><?php echo $heroe['description']; ?></textarea>
                            <label for="description">Descripción</label>
                            <span class="red-text">
                                <?php echo session('errores.description'); ?>
                                <?php
                                    $sessionERRdescription = session('errores.description');
                                    if (is_array($sessionERRdescription)) echo implode(', ', $sessionERRdescription);
                                    else echo $sessionERRdescription;
                                ?>
                            </span>
                        </div>

                        <div class="file-field col m10">
                            <div class="btn">
                                <span>Imagen</span>
                                <input type="file" name="imagen" id="imagen" accept="image/*">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                            <span class="red-text">
                                <?php
                                    $sessionERRimagen = session('errores.imagen');
                                    if (is_array($sessionERRimagen)) echo implode(', ', $sessionERRimagen);
                                    else echo $sessionERRimagen;
                                ?>
                            </span>
                        </div>
                        <div class="file-field col m2">
                            <a href="<?php echo $heroe['imagen']; ?>" target="_blank">
                                <img src="<?php echo $heroe['imagen']; ?>" alt="<?php echo $heroe['name']; ?>" width="100">
                            </a>
                        </div>
                        <div class="input-field col m12">
                            <i class="material-icons prefix">person</i>
                            <input type="text" class="validate" id="resourceURI" name="resourceURI" value="<?php echo $heroe['resourceURI']; ?>">
                            <label for="resourceURI">resourceURI</label>
                            <span class="red-text">
                                <?php
                                    $sessionERRresourceURI = session('errores.resourceURI');
                                    if (is_array($sessionERRresourceURI)) echo implode(', ', $sessionERRresourceURI);
                                    else echo $sessionERRresourceURI;
                                ?>
                            </span>
                        </div>

                        <div class="input-field col m12" id="wrapper-keys-and-values">
                            <div class="row">
                                <div class="col m12">
                                    <h4 class="fs-4">Comics</h4>
                                    <button id="add-key-value" class="waves-effect waves-light btn"><i class="material-icons right">cloud</i>Agregar comic</button>
                                </div>
                            </div>
                            <?php
                                $comics = json_decode($heroe['comics_items'], true);
                                if (count($comics) > 0) :
                            ?>
                                <?php foreach ($comics as $key => $value) : ?>
                                    <div id="comic_<?php echo $key; ?>" class="row">
                                        <div class="col m5">
                                            <input type="text" placeholder="Resource URI" class="form-control" name="comic_resourceURI[]" value="<?php echo $value['resourceURI']; ?>">
                                        </div>
                                        <div class="col m5">
                                            <input type="text" placeholder="Name" class="form-control" name="comic_name[]" value="<?php echo $value['name']; ?>">
                                        </div>
                                        <div class="col m2">
                                            <a href="javascript:;" data-iddelwrapper="comic_<?php echo $key; ?>" onclick="eliminarTagHTML(this)">
                                                <span class="material-icons" style="color:red;">delete</span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>

                        <div class="input-field col m12">
                            <label>
                                <input type="checkbox" name="publicado" <?php echo $heroe['publicado']; ?> value="1" />
                                <span>Publicado</span>
                            </label>
                            <span class="red-text">
                                <?php
                                    $sessionERRpublicado = session('errores.publicado');
                                    if (is_array($sessionERRpublicado)) echo implode(', ', $sessionERRpublicado);
                                    else echo $sessionERRpublicado;
                                ?>
                            </span>
                        </div>
                    </div>
                    <br />
                    <input type="submit" class="input-submit btn-large waves-effect waves-light white-text blue-grey darken-4" value="Actualizar">
                </form>
            </div>
        </div>
    </div>
<?php $this->endSection('contenido'); ?>



<?php $this->section('footerscripts'); ?>
    <script>
        jQuery(document).ready(function() {
            let numExtraControls = 0;
            jQuery('#add-key-value').click(function(e) {
                e.preventDefault();
                numExtraControls++;
                jQuery('#wrapper-keys-and-values').append('<div id="extraControl_'+numExtraControls+'" class="row"> <div class="col m5"> <input type="text" placeholder="Resource URI" class="form-control" name="comic_resourceURI[]"> </div> <div class="col m5"> <input type="text" placeholder="Name" class="form-control" name="comic_name[]"> </div> <div class="col m2"> <a href="javascript:;" data-iddelwrapper="extraControl_'+numExtraControls+'" onclick="eliminarTagHTML(this)"><span class="material-icons">delete</span></a> </div> </div>');
            });
        });
    </script>
<?php $this->endSection('footerscripts'); ?>