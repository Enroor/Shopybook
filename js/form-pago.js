$(document).ready(function () {
    $("#cesta").on('click', function () {
            $("#container-cesta").css("display", "block");
            $("#realizar-pago").css("display", "none"); //Si es false se mantiene el form
    });
    $("#pagar").on('click', function () {
            $("#container-cesta").css("display", "none");
            $("#realizar-pago").css("display", "block"); //Si es false se mantiene el form
    });
    $("#input-button").on('click', function () {
            $("#container-cesta").css("display", "none");
            $("#realizar-pago").css("display", "block"); //Si es false se mantiene el form
    });
    
});

