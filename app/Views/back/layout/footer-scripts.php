<?php
    $Aplicacion = config('Aplicacion');
    $router = \Config\Services::router();
    $metodoActual = $router->methodName();
    $controladorActual = $router->controllerName();
    $session = \Config\Services::session();
    $dataMetodoEjecutadho = str_replace('\\', '/', $controladorActual);
    $dataMetodoEjecutadho .= '::'.$metodoActual.'()';
?>
<script>
    console.log('[Footer] Método ejecutado: <?php echo $dataMetodoEjecutadho; ?>');
    base_url = '';
    // root_url = '';
    if (typeof base_url === 'undefined') {
        base_url = '<?php echo base_url(); ?>/';
    }
    // if (typeof root_url === 'undefined') {
    //     root_url = '<?php // echo ROOTURL; ?>';
    // }
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/98f9d31eea.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo base_url('js/helpers.js?version=2'); ?>"></script>
<?php echo $this->include('otros/datatables'); ?>



<?php if (GOOGLE_RECAPTCHA_VERSION === 2) : ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php elseif (GOOGLE_RECAPTCHA_VERSION === 3) : ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo GOOGLE_SITEKEY; ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo GOOGLE_SITEKEY; ?>', {action: 'developermarvel'}).then(function(token) {
                jQuery('.g-recaptcha-response').val(token);
            });
        });
    </script>
<?php endif; ?>



<script>
    jQuery(document).ready(function() {

        // Materialize
        jQuery(".dropdown-trigger").dropdown();
        jQuery('select:not(.select2)').formSelect();
        jQuery('.modal').modal({
            dismissible: true,
        });
        jQuery(document).ready(function(){
            jQuery('.tooltipped').tooltip();
        });

        // ToastR
        <?php if ($session->getFlashdata('toastr') !== null) : ?>
            jQuery(function () {
                let toastrMSG = 'Unknown';
                let toastrTITLE = '¡Ooops!';
                let toastrTIPO = 'error';
                let json = JSON.parse('<?php echo $session->getFlashdata('toastr'); ?>');
                let jsonKeys = Object.keys(json).length;

                if ((typeof(jsonKeys)==='number' && jsonKeys===3)) {
                    toastrMSG   = json.msg;
                    toastrTITLE = json.title;
                    toastrTIPO  = json.tipo;
                }
                showToastR(toastrMSG, toastrTIPO, toastrTITLE);
            })
            <?php endif; ?>

        // Función encargada de procesar imágenes TinyMCE
        function image_upload_handler (blobInfo, success, failure, progress) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', root_url + 'public/php/Tinymceprocessimg.php');

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function() {
                var json;

                if (xhr.status === 403) {
                    failure('HTTP Error: ' + xhr.status, { remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                showToastR(json.msg, json.msgtype, json.title);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                let pathIMG = json.location;
                let posFilename = pathIMG.indexOf(blobInfo.filename()); // Posición del nombre del archivo original
                let posInicial = posFilename - 15; // El 15 corresponde con la fecha y el guión bajo que pone Tinymceprocessimg.php
                let filenameFull = pathIMG.substring(posInicial, pathIMG.length);
                // Finalmente ponemos el path de la imagen dentro del popup TinyMCE
                success(base_url + 'uploads/' + filenameFull);
            };

            xhr.onerror = function () {
                failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        };

        // TinyMCE para imágenes
        tinymce.init({
            // Generales
            selector: '.tinymce_img',
            document_base_url: '<?php echo base_url('uploads');?>/',
            relative_urls: false,
            remove_script_host: true,
            plugins: 'image code emoticons',
            a_plugin_option: true,
            a_configuration_option: 400,
            a11y_advanced_options: true,
            image_caption: true,
            image_title: true,
            image_advtab: true,

            // Editor
            menubar: '',
            toolbar: 'image | emoticons | code',
            toolbar_mode: 'floating',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            height: 300,

            // Upload imágenes
            <?php
                // Existen dos formas de gestionar la subida de archivos al servidor:
                //  1. images_upload_url. Permite poner la ruta del archivo PHP que gestiona la carga de IMGs.
                //  2. images_upload_handler. Lo mismo que el anterior, sólo que permite manipular qué se hace en caso de éxito y error después de la carga.
            ?>
            images_upload_handler: image_upload_handler,
        });

        // TinyMCE con todas las opciones
        tinymce.init({
            // Generales
            selector: '.tinymce_full',
            document_base_url: '<?php echo base_url('uploads');?>/',
            relative_urls: false,
            remove_script_host: true,
            // El plugin tinydrive al parecer pone opción para seleccionar imágenes directo del servidor (?): https://www.tiny.cloud/docs/tinydrive/get-help/#gethelpwithtinydrive
            plugins: 'preview importcss searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
            a_plugin_option: true,
            a_configuration_option: 400,
            a11y_advanced_options: true,

            // Editor
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview | image media template link anchor codesample | a11ycheck ltr rtl | code',

            templates: [
                { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
                { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
                { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 300,

            image_caption: true,
            image_title: true,
            image_advtab: true,
            images_upload_handler: image_upload_handler,

            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            content_style: '.mymention{ color: gray; }',
            contextmenu: 'link image imagetools table',
        });

    });

    jQuery('#importar_heroes').click(function() {
        console.log('Se dio click a importar');
        jQuery('#spinner-gif').css('display', 'block');
    });

</script>
