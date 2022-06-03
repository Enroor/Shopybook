$(document).ready(function () {
    $("#perfil").on('click', function () { //Muestra los datos de perfil
        $("#datos-perfil").show();
        $("#datos-pedido").hide();
        $("#modificar-perfil").hide();
        return false;
    });
    $("#pedidos").on('click', function () { //Muestra los pedidos del usuario
        $("#datos-perfil").hide();
        $("#datos-pedido").show();
        $("#modificar-perfil").hide();
        return false;
    });
    $("#modifica-perfil").on('click', function () { //Muestra el formulario para modificar el perfil
        $("#datos-perfil").hide();
        $("#datos-pedido").hide();
        $("#modificar-perfil").show();
        return false;
    });
});