<?php
session_start();
require_once 'templates/header.php';
require_once('include/DB.php');
require_once('include/utils.php');

/* * *****BORRADO DE LIBRO***** */
if (isset($_GET['id'])) {
    DB::borrarLibro($_GET['id']); //Borramos el libro con el id devuelto via get
    header('Location: adminLibros.php');
}
/* * *******PAGINAR********* */
$totalLibros = DB::totalLibros(); //Total de libros para paginar
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

//El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * 8;

$libros = DB::obtieneLibros($offset); //Obtenemos los libros de la base de datos.
$error = '';
if (isset($_POST['anadir'])) {
    $isbn = sanearNums($_POST['isbn']);
    $titulo = limpiaTexto($_POST['titulo']);
    $autor = limpiaTexto($_POST['autor']);
    $precio = sanearNums($_POST['precio']);
    $stock = sanearNums($_POST['stock']);
    $image = $_FILES['image']['name'];
    $descripcion = limpiaTexto($_POST['descripcion']);
    $categoriaID = $_POST['select'];

    //Datos obligatorios para subir un libro
    if (!(isset($_POST['isbn'])) || empty($_POST['isbn']) || !(preg_match('/[0-9]{10,13}/', $_POST['isbn']))) {
        $error = 'Error: Debes ingresar un ISBN para el libro de entre 10 y 13 dígitos.';
    } elseif (!(isset($_POST['precio'])) || empty($_POST['precio'])) {
        $error = 'Error: Debes añadir un precio para el libro';
    } elseif (!(isset($_POST['titulo'])) || empty($_POST['titulo'])) {
        $error = 'Error: Debes añadir un titulo para el libro.';
    } else {
        //Si se quiere subir una imagen
        //Recogemos el archivo enviado por el formulario
        $archivo = $_FILES['image']['name'];
        //Si el archivo contiene algo y es diferente de vacio
        if (isset($archivo) && $archivo != "") {
            //Obtenemos algunos datos necesarios sobre el archivo
            $tipo = $_FILES['image']['type'];
            $tamano = $_FILES['image']['size'];
            $temp = $_FILES['image']['tmp_name'];

            //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
            if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 1048576))) {
                echo '<div><b>Error. El tipo de archivo debe ser .png o .jpg y el tamaño tiene que ser de 1MB como máximo.</b></div>';
            } else {
                //Si la imagen es correcta en tamaño y tipo
                //Se intenta subir al servidor
                if (move_uploaded_file($temp, 'uploads/' . $archivo)) {
                    //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                    chmod('uploads/' . $archivo, 0777);
                } else {
                    //Si no se ha podido subir la imagen, mostramos un mensaje de error
                    echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
                }
            }
        } else {
            $image = 'fondo_portada.jpg';
        }
        //Ejecutamos la consulta sql para insertar los datos del libro.
        $libro = DB::insertarLibro($isbn, $titulo, $autor, $precio, $stock, $image, $descripcion, $categoriaID);
        if (!$libro) {
            $error = '<b>Error:</b> fallo al insertar nuevo libro, el ISBN ya existe.';
        } else {
            header('Location: adminLibros.php');
        }
    }
}
?>
<div id="anadir">
    <a href="#" id="show"><img name="anadir" src="imgs/anadir.png" alt="anadir"/>Añadir libro</a><br/>
    <span class="obligatorio"><?php echo $error; ?></span>
</div>
<div>
    <!--Formulario para insertar libros-->
    <form id="insertar-form" action="adminLibros.php" method="post" enctype="multipart/form-data">
        <div id="container-datos">
            <div class="datos">
                <label>ISBN:</label>
                <input type="text" name="isbn" minlength="10" maxlength="13" required="obligatorio" size='13'><span class="obligatorio"> *</span><br/>
                <label>Título:</label>
                <input type="text" name="titulo"><br/>
                <label>Autor:</label>
                <input type="text" name="autor" id='autor'>
                <label>&nbsp&nbsp&nbsp&nbsp&nbsp Categoría: </label>
                <select name="select">
                    <?php
                    $categorias = DB::obtieneTodasCategorias(); //Obtenemos las categorías de la base de datos.
                    foreach ($categorias as $c):
                        ?>
                        <option value="<?php echo $c->getid(); ?>"><?php echo $c->getnombre(); ?></option>
                    <?php endforeach; ?>
                </select><br/>
                <div>
                    <label>Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="00.01" max="100.00" value='00.01'> € 
                    <label>&nbsp&nbsp&nbsp&nbsp&nbsp Stock:</label>
                    <input type="number" id="stock" name="stock" min="0" value='1'>
                    <label>
                        &nbsp&nbsp&nbsp&nbsp <b>Imágen:</b> &nbsp&nbsp
                        <input type="file" name="image" accept=".jpg, .jpeg, .png"/>
                    </label>
                </div>
                <br/><br/>
                <label>Descripción:</label><br/>
                <textarea name="descripcion" rows="5" cols="20"></textarea><br/><br/>
            </div>
            <div id="footer-form">
                <p class="aviso">
                    <span class="obligatorio"> * </span>los campos son obligatorios.
                </p>   
                <input type="submit" name="anadir" value="Subir libro">
               <input type="submit" name="cancelar" value="Cancelar" id="ocultar">
            </div>
        </div>
    </form>
</div>
<div>
    <!-- TABLA PARA MOSTAR LOS LIBROS -->
    <table id='tabla-libros-admin'>
        <thead>
            <tr>
                <th>ID</th>
                <th>ISBN</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($libros as $l):
                ?>     
                <tr>
                    <td><?php echo $l->getid(); ?></td>
                    <td><?php echo $l->getisbn(); ?></td>
                    <td><?php echo $l->gettitulo(); ?></td>
                    <td><?php echo $l->getautor(); ?></td>
                    <td>
                        <?php
                        $nom = DB::obtieneCategoriaId($l->getcategoriaID()); //Obtenemos nombre de la categoria pasandoe el id del libro
                        if ($nom)
                            echo $nom->getnombre();
                        else
                            echo $nom = 'Sin categoría';
                        ?>
                    </td>
                    <td><?php echo number_format($l->getprecio(), 2) ?> €</td>
                    <td><?php echo $l->getstock(); ?></td>
                    <td>
                        <button type="submit" name="editar" >
                            <a href="editar.php?id=<?php echo $l->getid(); ?>" class='editar'>
                                <img class='edicion'  src='imgs/editar.png' alt='editar'/>
                            </a>
                        </button>
                        <button type="submit" name="eliminar">
                            <a href="adminLibros.php?id=<?php echo $l->getid(); ?>" class="eliminar">
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
$paginas = ceil($totalLibros[0] / 8);
if (isset($_POST['palabraClave']))
    
    ?>
<div class="row">
    <div>
        <ul class="pagination">
            <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
            <?php if ($pagina > 1) { ?>
                <li>
                    <a href="adminLibros.php?pagina=<?php echo $pagina - 1 ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
            <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                <li class="<?php if ($x == $pagina) echo "active" ?>">
                    <a href="adminLibros.php?pagina=<?php echo $x ?>">
                        <?php echo $x ?></a>
                </li>
            <?php } ?>
            <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
            <?php if ($pagina < $paginas) { ?>
                <li>
                    <a href="adminLibros.php?pagina=<?php echo $pagina + 1 ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="row" id="page-actual">
        <div> 
            <p>Página <b><?php echo $pagina; ?></b> de <?php echo $paginas; ?> </p>
            <p>Total de libros: <b> <?php echo $totalLibros[0]; ?></b></p>
        </div>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>

