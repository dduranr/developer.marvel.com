<?php $this->extend('back/layout/main'); ?>

<?php $this->section('titulo'); ?><?php echo APPNAME; ?> - Editar configuración<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col m12">
                    <h3>Editando conf: <span class="bbottom-1"><?php echo $item_data['nombre']; ?></span></h3>
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
                        'class'   => 'col s12',
                        'id'      => 'form-editar-configuraciones',
                        'csrf_id' => 'csrf'
                    ];
                    echo form_open(base_url('backend/configuraciones/update/'.$item_data['id']), $attributes);

                    foreach ($item_data['datos']['configuraciones'] as $conf_key => $conf_data) : ?>
                        <div class="row">
                            <?php if ((int)$conf_key===1) : ?>
                                <div class="col s12">
                                    <span class="nombre badge black badgeCustom1">Nombre/título de la aplicación</span>
                                </div>
                                <div class="input-field col s6">
                                    <i class="material-icons prefix">text_fields</i>
                                    <input type="text" name="appname" id="appname" class="validate" value="<?php echo $conf_data['appname']; ?>" />
                                    <label for="appname">Appname</label>
                                    <span class="red-text">
                                        <?php
                                            $sessionERRappname = session('errores.appname');
                                            if (is_array($sessionERRappname)) echo implode(', ', $sessionERRappname);
                                            else echo $sessionERRappname;
                                        ?>
                                    </span>
                                </div>
                            <?php elseif ((int)$conf_key===2) : ?>
                                <div class="col s12">
                                    <span class="nombre badge black badgeCustom1">Modo mantenimiento (esto tendrá efecto sólo sobre http://developer-marvel-covalto.local/backend/users/register)</span>
                                </div>
                                <div class="input-field col s6">
                                    <i class="material-icons prefix">build</i>
                                    <select class="validate" name="mantenimiento" id="mantenimiento" required>
                                        <option value="0" <?php echo ($conf_data['mantenimiento']===0) ? 'selected' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($conf_data['mantenimiento']===1) ? 'selected' : ''; ?>>Sí</option>
                                    </select>
                                    <label for="mantenimiento">Mantenimiento</label>
                                    <span class="red-text">
                                        <?php
                                            $sessionERRmantenimiento = session('errores.mantenimiento');
                                            if (is_array($sessionERRmantenimiento)) echo implode(', ', $sessionERRmantenimiento);
                                            else echo $sessionERRmantenimiento;
                                        ?>
                                    </span>
                                </div>
                                <?php elseif ((int)$conf_key===3) : ?>
                                    <div class="col s12">
                                        <span class="nombre badge black badgeCustom1">Email al que deberán comunicarse en caso de error</span>
                                    </div>
                                    <div class="input-field col s6">
                                        <i class="material-icons prefix">email</i>
                                        <input type="text" name="emailsupport" id="emailsupport" class="validate" value="<?php echo $conf_data['emailsupport']; ?>" />
                                        <label for="emailsupport">Email de soporte</label>
                                        <span class="red-text">
                                            <?php
                                                $sessionERRemailsupport = session('errores.emailsupport');
                                                if (is_array($sessionERRemailsupport)) echo implode(', ', $sessionERRemailsupport);
                                                else echo $sessionERRemailsupport;
                                            ?>
                                        </span>
                                    </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <br />
                    <input type="submit" class="input-submit btn-large waves-effect waves-light white-text blue-grey darken-4" value="Actualizar">
                </form>
            </div>
        </div>
    </div>
<?php $this->endSection('contenido'); ?>
