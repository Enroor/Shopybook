<?php
session_start();
require_once 'templates/header.php';
require_once('include/DB.php');
require_once('include/utils.php');

/* * *******PAGINAR********* */
$totalCategorias = DB::totalCategorias(); //Total de categorias para paginar
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

//El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * 8;

$categorias = DB::obtieneCategorias($offset); //Obtenemos las categorías de la base de datos.
$error = '';
if (isset($_POST['anadir'])) {
    if (!(isset($_POST['nombre'])) && empty($_POST['nombre'])) {
        header('Location: categorias.php');
    } else {
        $categoria = limpiaTexto($_POST['nombre']);
        //Ejecutamos la consulta sql para insertar el nombre de la categoría.
        $categoria = DB::insertarCategoria($categoria);

        if (!$categoria) {
            $error = '<b>Error:</b> la categoría ya existe.';
        } else {
            header('Location: categorias.php');
        }
    }
}
?>
<div id="anadir-categoria">
    <a href="#" id="show-form"><img name="anadir" src="imgs/anadir.png" alt="anadir"/>Añadir categoría</a><br/>
    <span class="obligatorio"><?php echo $error; ?></span>
</div>
<div>
    <form id="insertar-form-categoria" action="categorias.php" method="post">
        <div id="container-datos">
            <div class="datos">
                <label>Nombre de la categoría:</label>
                <input type="text" name="nombre" required="obligatorio" id="nom-categoria"><span class="obligatorio"> *</span><br/>
                <div id="footer-form">
                    <p class="aviso" id="aviso-categoria">
                        <span class="obligatorio"> * </span>los campos son obligatorios.
                    </p>   
                    <input type="submit" name="anadir" value="Añadir">
                    <input type="submit" name="cancelar" value="Cancelar" id="ocultar-form">
                </div>
            </div>
    </form>
</div>
<div>
    <!-- TABLA PARA MOSTAR LAS CATEGORIAS -->
    <table id='tabla-categorias'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['id'])) {
                DB::borrarCategoria($_GET['id']);
                header('Location: categorias.php');
            }
            foreach ($categorias as $c):
                ?>     
                <tr>
                    <td><?php echo $c->getid(); ?></td>
                    <td><?php echo $c->getnombre(); ?></td>
                    <td>
                        <button type="submit" name="eliminar">
                            <a href="categorias.php?id=<?php echo $c->getid(); ?>" class="eliminar" onclick="return();">
                                <img class='edicion' src='imgs/eliminar.png' alt='eliminar'/>
                            </a>
                        </button>
                    </td>
                </tr>    
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
//Para obtener las páginas dividimos el total entre los productos por página, y redondeamos hacia arriba
$paginas = ceil($totalCategorias[0] / 8);
?>
<div class="row">
    <div>
        <ul class="pagination">
            <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
            <?php if ($pagina > 1) { ?>
                <li>
                    <a href="categorias.php?pagina=<?php echo $pagina - 1 ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
            <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                <li class="<?php if ($x == $pagina) echo "active" ?>">
                    <a href="categorias.php?pagina=<?php echo $x ?>">
                        <?php echo $x ?></a>
                </li>
            <?php } ?>
            <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
            <?php if ($pagina < $paginas) { ?>
                <li>
                    <a href="categorias.php?pagina=<?php echo $pagina + 1 ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="row" id="page-actual">
        <div> 
            <p>Página <b><?php echo $pagina; ?></b> de <?php echo $paginas; ?> </p>
            <p>Total de categorías: <b> <?php echo $totalCategorias[0]; ?></b></p>
        </div>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>

