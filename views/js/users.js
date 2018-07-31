/**
 * SUBIENDO LA FOTO DEL USUARIO
 */

$(".nuevaFoto").change(function() {

    var imagen = this.files[0];
    console.log("imagen", imagen)

    /**
     * VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
    */

    if (imagen['type'] != 'image/jpeg' && imagen['type'] != 'image/png') {
        $('.nuevaFoto').val('');

        swal({
            type: 'error',
            title: "Error al subir la imagen",
            text: "¡La imagen debe estar en formato JPG O PNG!",
            confirmButtonText: '¡Cerrar!'
        });
    } else if (imagen['size'] > 5000000) {
        /** 
         * VALIDAMOS EL TAMAÑO DE LA IMAGEN
        */
        $('.nuevaFoto').val('');

        swal({
            type: 'error',
            title: "Error al subir la imagen",
            text: "¡La imagen no debe pesar mas de 5MB!",
            confirmButtonText: '¡Cerrar!'
        });
    } else {
        /** 
         * SI PASA LOS FILTRO DE SEGURIDAD
        */
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);

        $(datosImagen).on('load', function (event) {
            var rutaImagen = event.target.result;

            $('.preview').attr('src', rutaImagen)
        })
    }

})

/** 
 * EDITAR USUARIO
*/

$('.btnEditarUsuario').click( function() {
    var idUsuario = $(this).attr('idUsuario')
    
    var datos = new FormData();
    datos.append('idUsuario', idUsuario)

    $.ajax({
        url: 'ajax/users.ajax.php',
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta){
            // console.log(respuesta)
            $('#editarNombre').val(respuesta['name'])
            $('#editarUsuario').val(respuesta['user'])
            $('#passwordActual').val(respuesta['password'])
            $('#editarPerfil').val(respuesta['profile'])
            $('#fotoActual').val(respuesta['avatar'])

            if (respuesta['profile'] == 1) {
                $('#editarPerfil').html('Administrador')
            } else if (respuesta['profile'] == 2) {
                $('#editarPerfil').html('Especial')
            } else if(respuesta['profile'] == 3) {
                $('#editarPerfil').html('Vendedor')
            }

            if (respuesta['avatar'] != '') {
                $('.preview').attr('src', respuesta['avatar'])
            }
        }
    })
})

/** 
 * ACTIVAR USUARIO
*/

$('.btnActivar').click(function() {
    var idUser = $(this).attr('idUsuario');
    var statusUser = $(this).attr('status');

    var datos = new FormData();

    datos.append('activarId', idUser)
    datos.append('activarUsuario', statusUser)

    $.ajax({
        url: 'ajax/users.ajax.php',
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(respuesta) {

        }
    })

    if (statusUser == 0) {
        $(this).removeClass('btn-success')
        $(this).addClass('btn-danger')
        $(this).html('Desactivado')
        $(this).attr('status', 1)
    } else {
        $(this).removeClass('btn-danger')
        $(this).addClass('btn-success')
        $(this).html('Activado')
        $(this).attr('status', 0)
    }
})

/** 
 * REVISAR SI EL USUARIO YA ESTA REGISTRADO
*/

$('#nuevoUsuario').change(function() {
    $('.alert').remove();

    var user = $(this).val();

    var datos = new FormData();
    datos.append('validarUsuario', user)

    $.ajax({
        url: 'ajax/users.ajax.php',
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta) {
            // console.log(respuesta)
            if (respuesta) {
                $('#nuevoUsuario').parent().after('<div class="alert alert-warning">Este usuario ya esta registrado</div>')
                $('#nuevoUsuario').val('')
            } 
        }
    })
})

/** 
 * ELIMINAR USUARIO
*/
$('.btnDeleteUser').click(function() {

    var idUser = $(this).attr('idUsuario');
    var fotoUser = $(this).attr('fotoUsuario');
    var user = $(this).attr('usuario');

    swal({
        title: '¿Estas seguro de borrar el usuario?',
        type: 'warning',
        showCancelButton: true,
        confirButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        calcelButtonText: 'Cancelar',
        confirButtonText: 'Si, borrar Usuario!'
    }).then((result) => {
        if (result.value) {
            window.location = 'index.php?ruta=users&idUser='+idUser+'&fotoUser='+fotoUser+'&user='+user
        }
    })
})