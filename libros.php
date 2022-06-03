<?php
require_once 'templates/header.php';
require_once('include/DB.php');
require_once('include/utils.php');

/* * *******PAGINAR********* */
$totalLibros = DB::totalLibros(); //Total de libros para paginar
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

//El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * 8;

/* * ****OBTENEMOS LIBROS****** */
$libros = DB::obtieneLibros($offset); //Obtenemos los libros de la base de datos.
$error = '';
$msg = '';

/* * ********BUSCADOR********* */
//Si se ha mandado el formulario del buscador de palabras del header.
if (isset($_POST['palabraClave']) && !empty($_POST['palabraClave'])) {
    //Obtenemos el libro de la base datos a través de la palabra clave.
    $libroF = DB::buscador(limpiaTexto($_POST['palabraClave']));
    //Si se encuentran resultados se muestran
    if (!is_null($libroF)) {
        ?>
        <div class="card-libro-find">
            <img class="portada" src="uploads/<?php echo $libroF->getimagen(); ?>" alt="<?php echo $libroF->gettitulo(); ?>" 
                 title="<?php echo $libroF->gettitulo(); ?>"><br/>
                 <?php
                 echo "<h5>" . $libroF->gettitulo() . "</h5><br/>";
                 echo "<p class='autor'>" . $libroF->getautor() . "</p><br/>";
                 ?>
            <p class='precio-libro'>
                <?php
                echo number_format($libroF->getprecio(), 2) . "€<br/>";
                ?>
            </p>
            <p class='btns-libros'>
                <?php
                if (isset($_SESSION['usuario']) && ($_SESSION['usuario'] === 'admin')) {
                    ?> 
                    <a href='editar.php?id=<?php echo $libroF->getid(); ?>'><input class='btn-libro-editar-admin' type='submit' name='editar' value='Editar'/></a>
                    <?php
                } else {
                    ?>
                    <a href='libro.php?id=<?php echo $libroF->getid(); ?>'><input class='btn-libro-ver' type='submit' name='ver' value='Ver'/></a> 
                    <br/>
                    <?php
                    $found = false;
                    //Si el libro está en el carrito
                    if (isset($_SESSION["cart"])) {
                        foreach ($_SESSION["cart"] as $c) {
                            if ($c["id"] == $libroF->getid()) {
                                $found = true;
                            }
                        }
                    }
                    if ($found) {
                        ?>
                    <div style='margin-left: 2px;'>
                        <input class='btn-libro-anadido' type='submit' value='Añadido al carrito'"/>
                    </div>
                    <?php
                } else {
                    ?>
                    <form method="post" action='addCarrito.php?id=<?php echo $libroF->getid(); ?>'>   
                        <a href='addCarrito.php?id=<?php echo $libroF->getid(); ?>' class='anadir-carrito' style='margin-left: 2px;'>
                            <input class='btn-libro-anadir' type='submit' name='anadir' value='Añadir al carrito'"/>
                        </a>
                        <input type="hidden" name="cantidad" value="1" style="width:100px;" min="1" class="form-control" placeholder="Cantidad">
                    </form>
                    <?php
                }
            }
            ?>
        </p>
        </div>
        <?php
    } else {
        $error = 'No hay resultados sobre tu búsqueda.';
    }
    ?>
    <div>
        <h1 style='text-align: center;'><?php echo $error ?></h1>
    </div>
    <?php
} else {
    ?>
    <!---------------SI NO SE HA BUSCADO, SE MUESTRAN TODOS LOS LIBROS---------------->
    <div id='libros'>
        <?php
        //Recorremos el objeto $libro y obtenemos los datos.
        foreach ($libros as $libro) {
            ?>
            <div class="card-libro">
                <img class="portada" src="uploads/<?php echo $libro->getimagen(); ?>" alt="<?php echo $libro->gettitulo(); ?>" 
                     title="<?php echo $libro->gettitulo(); ?>"><br/>
                     <?php
                     echo "<h5>" . $libro->gettitulo() . "</h5><br/>";
                     echo "<p class='autor'>" . $libro->getautor() . "</p><br/>";
                     echo "<p class='precio-libro'>" . number_format($libro->getprecio(), 2) . " €</p><br/>";
                     ?>
                <p class='btns-libros'>
                    <?php
                    //Si el usuario es admin monstramos el botón editar(panel admin)
                    if (isset($_SESSION['usuario']) && ($_SESSION['usuario'] === 'admin')) {
                        ?> 
                        <a href='editar.php?id=<?php echo $libro->getid(); ?>'><input class='btn-libro-editar-admin' type='submit' name='editar' value='Editar'/></a>
                        <?php
                    } else {
                        //Botón para ver el libro según la id.
                        ?>
                        <a href='libro.php?id=<?php echo $libro->getid(); ?>'><input class='btn-libro-ver' type='submit' name='ver' value='Ver'/></a> 
                        <br/>
                        <?php
                        $found = false;
                        //Si el libro está en el carrito
                        if (isset($_SESSION["cart"])) {
                            foreach ($_SESSION["cart"] as $c) {
                                if ($c["id"] == $libro->getid()) {
                                    $found = true;
                                }
                            }
                        }
                        //Si es verdadero es que está en la sesión del carrito y mostramos Añadido al carrito.
                        if ($found) {
                            ?>
                        <div>
                            <input class='btn-libro-anadido' type='submit' value='Añadido al carrito'"/>
                        </div>
                        <?php
                    } else {
                        //Si el libro encontrado no tiene stock, msotramos Sin stock.
                        if ($libro->getstock() === 0) {
                            ?>
                            <div>
                                <input class='btn-libro-no-disponible' type='submit' value='Sin stock'"/>
                            </div>
                            <?php
                            //mostramos botón de añadir al carrito.
                        } else {
                            ?>    
                            <form method="post" action='addCarrito.php?id=<?php echo $libro->getid(); ?>'>   
                                <a href='addCarrito.php?id=<?php echo $libro->getid(); ?>' class='anadir-carrito'>
                                    <input class='btn-libro-anadir' type='submit' name='anadir' value='Añadir al carrito'"/>
                                </a>
                                <input type="hidden" name="cantidad" value="1" min="1" class="form-control" placeholder="Cantidad">
                            </form>
                            <?php
                        }
                    }
                }
                ?>
                </p>
            </div>
        <?php } ?>
    </div>
    <?php
    //Para obtener las páginas dividimos el total entre los productos por página, y redondeamos hacia arriba
    $paginas = ceil($totalLibros[0] / 8);
    ?>
    <div class="row">
        <div>
            <ul class="pagination">
                <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
                <?php if ($pagina > 1) { ?>
                    <li>
                        <a href="index.php?pagina=<?php echo $pagina - 1 ?>">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php } ?>
                <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
                <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                    <li class="<?php if ($x == $pagina) echo "active" ?>">
                        <a href="index.php?pagina=<?php echo $x ?>">
                            <?php echo $x ?></a>
                    </li>
                <?php } ?>
                <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
                <?php if ($pagina < $paginas) { ?>
                    <li>
                        <a href="index.php?pagina=<?php echo $pagina + 1 ?>">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="row" id="page-actual">
            <div>
                <p>Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php require_once 'templates/footer.php'; ?>