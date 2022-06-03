$(document).ready(function () {
    $("#ocultar").on('click', function () {
        if (confirm("¿Seguro que quieres cancelar la subida?")) {
            $("#insertar-form").hide(); //si se pulsa acpetar ocultamos el form para añadir libros (panel admin)
            $('input[type="text"]').val(''); //borramos los datos del input text
        } else {
            $("#insertar-form").show(); //Si es false se mantiene el form
        }
        return false;
    });

    $("#show").on('click', function () {
        $("#insertar-form").show();
        return false;
    });
});



 