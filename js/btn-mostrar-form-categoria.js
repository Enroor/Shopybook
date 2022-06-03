$(document).ready(function () {
    $("#ocultar-form").on('click', function () {
        if (confirm("¿Seguro que quieres cancelar la subida?")) {
            $("#insertar-form-categoria").hide(); //si se pulsa acpetar ocultamos el form añadir categorias (panel admin)
            $('input[type="text"]').val(''); //borramos los datos del input text
        } else {
            $("#insertar-form-categoria").show(); //Si es false se mantiene el form
        }
        return false;
    });

    $("#show-form").on('click', function () {
        $("#insertar-form-categoria").show();
        return false;
    });
});
