<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Editar usuario<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col m12">
                    <h3>Editando al usuario <span class="bbottom-1"><?php echo $user['name'] . ' ' . $user['apellidop']; ?></span></h3>
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
                        'id'      => 'form-edit-user',
                        'csrf_id' => 'csrf'
                    ];
                    echo form_open_multipart(base_url('backend/users/update/' . $user['id']), $attributes);
                ?>
                    <div class="row">
                        <div class="input-field col m4">
                            <i class="material-icons prefix">text_fields</i>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $user['name']; ?>" required>
                            <label for="nombre">Nombre</label>
                            <span class="red-text">
                                <?php echo session('errores.nombre'); ?>
                                <?php
                                    $sessionERRnombre = session('errores.nombre');
                                    if (is_array($sessionERRnombre)) echo implode(', ', $sessionERRnombre);
                                    else echo $sessionERRnombre;
                                ?>
                            </span>
                        </div>
                        <div class="input-field col m4">
                            <i class="material-icons prefix">text_fields</i>
                            <input type="text" id="apellidop" name="apellidop" value="<?php echo $user['apellidop']; ?>">
                            <label for="apellidop">Apellidop</label>
                            <span class="red-text">
                                <?php echo session('errores.apellidop'); ?>
                                <?php
                                    $sessionERRapellidop = session('errores.apellidop');
                                    if (is_array($sessionERRapellidop)) echo implode(', ', $sessionERRapellidop);
                                    else echo $sessionERRapellidop;
                                ?>
                            </span>
                        </div>
                        <div class="input-field col m4">
                            <i class="material-icons prefix">email</i>
                            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" readonly disabled>
                            <label for="email">Email</label>
                            <span class="red-text">
                                <?php echo session('errores.email'); ?>
                                <?php
                                    $sessionERRemail = session('errores.email');
                                    if (is_array($sessionERRemail)) echo implode(', ', $sessionERRemail);
                                    else echo $sessionERRemail;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4">
                            <i class="material-icons prefix">lock</i>
                            <input type="password" id="pass" name="pass" value="<?php echo old('pass'); ?>">
                            <label for="pass">Contraseña</label>
                            <span class="red-text">
                                <?php echo session('errores.pass'); ?>
                                <?php
                                    $sessionERRpass = session('errores.pass');
                                    if (is_array($sessionERRpass)) echo implode(', ', $sessionERRpass);
                                    else echo $sessionERRpass;
                                ?>
                            </span>
                            <div>
                                <p>Requisitos de contraseña:</p>
                                <ol>
                                    <li><strong>Al menos 2 números</strong></li>
                                    <li><strong>Al menos 2 letras minúsculas</strong></li>
                                    <li><strong>Al menos 2 letras mayúsculas</strong></li>
                                    <li><strong>Al menos 2 caracteres especiales: .!?+@#$%&*_-()[]{}^/</strong></li>
                                    <li><strong>Al menos 12 caracteres en total</strong></li>
                                </ol>
                            </div>
                        </div>
                        <div class="input-field col m4">
                            <i class="material-icons prefix">lock</i>
                            <input type="password" id="pass-c" name="pass-c" value="<?php echo old('pass-c'); ?>">
                            <label for="pass-c">Contraseña (confirmar)</label>
                            <span class="red-text">
                                <?php echo session('errores.pass-c'); ?>
                                <?php
                                    $sessionERRpassc = session('errores.pass-c');
                                    if (is_array($sessionERRpassc)) echo implode(', ', $sessionERRpassc);
                                    else echo $sessionERRpassc;
                                ?>
                            </span>
                        </div>
                    </div>

                    <?php if($user['group'] !== "2") : ?>
                        <div class="row">

                            <div class="file-field col m6">
                                <div class="btn">
                                    <span>Imagen de perfil</span>
                                    <input type="file" name="imgprofile" id="imgprofile" accept="image/*">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                                <div>La imagen de perfil debe ser cuadrada, de hasta 300 ó 400 pixeles por lado.</div>
                                <span class="red-text">
                                    <?php
                                        $sessionERRimgprofile = session('errores.imgprofile');
                                        if (is_array($sessionERRimgprofile)) echo implode(', ', $sessionERRimgprofile);
                                        else echo $sessionERRimgprofile;
                                    ?>
                                </span>
                            </div>

    <!--
        TinyMCE ya está configurado (con el perfil tinymce_img que cree) para que se cargue la imagen mediante AJAX, no hay que hacer nada más que descomentar este campo
                            <div class="input-field col m6">
                                <p>&nbsp;</p>
                                <i class="material-icons prefix">image</i>
                                <textarea class="tinymce_img" name="imgprofile" id="imgprofile"><?php echo $user['imgprofile']; ?></textarea>
                                <label for="imgprofile">Imagen de perfil</label>
                                <span class="red-text">
                                    <?php // echo session('errores.imgprofile'); ?>
                                    <?php
                                        // $sessionERRimgprofile = session('errores.imgprofile');
                                        // if (is_array($sessionERRimgprofile)) echo implode(', ', $sessionERRimgprofile);
                                        // else echo $sessionERRimgprofile;
                                    ?>
                                </span>
                            </div>
    -->
                            <div class="input-field col m6">
                                <p>&nbsp;</p>
                                <i class="material-icons prefix">format_align_left</i>
                                <textarea class="tinymce_full" name="descripcion" id="descripcion"><?php echo $user['descripcion']; ?></textarea>
                                <label for="descripcion">Descripción</label>
                                <span class="red-text">
                                    <?php echo session('errores.descripcion'); ?>
                                    <?php
                                        $sessionERRdescripcion = session('errores.descripcion');
                                        if (is_array($sessionERRdescripcion)) echo implode(', ', $sessionERRdescripcion);
                                        else echo $sessionERRdescripcion;
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php //if (in_array((int)$session['userid'], ADMINS)) : ?>
                    <?php if(false) : ?>
                        <div class="row">
                            <div class="input-field col m12">
                                <label>
                                    <input type="checkbox" name="publicado" value="1" <?php echo ($user['publicado'] == 1) ? 'checked' : ''; ?> />
                                    <span>Publicado (significa que aspirante confirmó su registro)</span>
                                </label>
                                <span class="red-text">
                                    <?php echo session('errores.publicado'); ?>
                                    <?php
                                        $sessionERRpublicado = session('errores.publicado');
                                        if (is_array($sessionERRpublicado)) echo implode(', ', $sessionERRpublicado);
                                        else echo $sessionERRpublicado;
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <br />
                    <input type="submit" class="input-submit btn-large waves-effect waves-light white-text blue-grey darken-4" value="Actualizar">
                </form>
            </div>
        </div>
    </div>
<?php $this->endSection('contenido'); ?>
