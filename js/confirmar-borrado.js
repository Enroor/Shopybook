$(document).ready(function () {
    $(".eliminar").on('click', function() {
        if (confirm("¿Seguro que quieres eliminar el libro?")) {
            return true;
        }
        return false;
    });
});
