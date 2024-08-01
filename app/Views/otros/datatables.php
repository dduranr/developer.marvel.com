<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('#table_heroes').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 50, 100, -1],
                [5, 10, 50, 100, "Todos"]
            ],
            "order": [ [0, "asc"] ],
            "ajax": {
                "url": '<?php echo base_url('datatables/heroes'); ?>',
                "type": "GET",
                "contentType": "application/json; charset=utf-8"
            },
            "columns": [
                {data: "id"},
                {data: "id_marvel"},
                {data: "name"},
                {data: "thumbnail_path"},
                {data: "thumbnail_extension"},
                {data: "origen"},
                {data: "publicado"},
                {data: "editar"},
                {data: "eliminar"}
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ Heroes",
                "zeroRecords": "No hay Heroes",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay Heroes disponibles",
                "infoFiltered": "(Total: _MAX_ Heroes)",
                "search": "Buscar"
            },
            "columnDefs": [
                {
                    "targets": 4,
                    "visible": false
                },
                {
                    "targets": 5,
                    "visible": false
                },
                {
                    "targets": 7,
                    "orderable": false,
                },
                {
                    "targets": 8,
                    "orderable": false
                },
            ]
        });

        jQuery('#table_configuraciones').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 50, 100, -1],
                [5, 10, 50, 100, "Todos"]
            ],
            "order": [ [0, "asc"] ],
            "ajax": {
                "url": '<?php echo base_url('datatables/configuraciones'); ?>',
                "type": "GET",
                "contentType": "application/json; charset=utf-8"
            },
            "columns": [
                {data: "id"},
                {data: "nombre"},
                {data: "datos"},
                {data: "publicado"},
                {data: "editar"}
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ Configuraciones",
                "zeroRecords": "No hay Configuraciones",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay Configuraciones disponibles",
                "infoFiltered": "(Total: _MAX_ Configuraciones)",
                "search": "Buscar"
            }
        });

    });
</script>