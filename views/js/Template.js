$(document).ready(function () {
    $('.sidebar-menu').tree()

    $('.dtable').DataTable({
        "language": {
            // jquery.dataTables.js cambio de lenguage #11340
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            },
            // jquery.dataTables.js cambio de lenguage #11418
            "info": "Ver de _START_ - _END_ de _TOTAL_ entradas",
            // jquery.dataTables.js cambio de lenguage #11434
            "infoEmpty": "Ver 0 - 0 de 0 entradas",
            // jquery.dataTables.js cambio de lenguage #11579
            "lengthMenu": 'Mostrar <select>'+
			            '<option value="2">2</option>'+
			            '<option value="10">10</option>'+
			            '<option value="20">20</option>'+
			            '<option value="-1">Todo</option>'+
                        '</select> entradas',
            // jquery.dataTables.js cambio de lenguage #11659
            "search": "Buscar:",
            // jquery.dataTables.js cambio de lenguage #11381
            "emptyTable": "No hay datos disponibles en la tabla",
            // jquery.dataables.js cambio de lenguage #11461
            "infoFiltered": "(Filtrado de _MAX_ entradas totales)",
            // jquery.dataables.js cambio de lenguage #11717
            "zeroRecords": "No se encontraron registros"
        },
        // responsive: true
    });
})