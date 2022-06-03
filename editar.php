<?php
session_start();
require_once 'templates/header.php';
require_once('include/DB.php');
$libro = DB::obtieneLibro($_GET['id']);
$msg = '';

//Si mandamos el formulario
if (isset($_POST['actualizar'])) {
    $id = $_GET['id'];
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $image = $_FILES['image']['name'];
    $descripcion = $_POST['descripcion'];
    $categoriaID = $_POST['select'];

    //Datos obligatorios para subir un libro
    if (!(isset($_POST['isbn'])) || empty($_POST['isbn'])) {
        $error = 'Debes ingresar un ISBN para el libro.';
    } elseif (!(isset($_POST['precio'])) || empty($_POST['precio'])) {
        $error = 'Debes añadir un precio para el libro,';
    } elseif (!(isset($_POST['titulo'])) || empty($_POST['titulo'])) {
        $error = 'Debes añadir un titulo para el libro.';
    } else {
        //Si se quiere subir una imagen
        //Recogemos el archivo enviado por el formulario
        $archivo = $_FILES['image']['name'];
        //Si el archivo contiene algo y es diferente de vacio
        if (isset($archivo) && $archivo != "") {
            //Obtenemos algunos datos necesarios sobre el archivo
            $tipo = $_FILES['image']['type']; //Tipo de archivo
            $tamano = $_FILES['image']['size']; //Tamaño de archivo
            $temp = $_FILES['image']['tmp_name']; //nombre temporal
            //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
            if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 9000000))) {
                echo '<div><b>Error. El tipo de archivo debe ser .png o .jpg y el tamaño tiene que ser de 900KB como máximo.</b></div>';
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
            $image = $libro->getimagen(); //Si no se modifica la imagen la recuperamos de la BD
        }
        //Ejecutamos la consulta sql
        $libro = DB::actualizarLibro($id, $isbn, $titulo, $autor, $precio, $stock, $image, $descripcion, $categoriaID);
        if ($libro) {
            header('Location: adminLibros.php');
            $msg = 'Libro actualizado correctamente.';
        }
    }
}
?>
<span><?php echo $msg; ?></span>
<form id="actualizar-form" action="editar.php?id=<?php echo $libro->getid(); ?>" method="post" enctype="multipart/form-data">
    <div id="container-datos">
        <div class="datos">
            <label>ISBN:</label>
            <span class="obligatorio"><?php echo (isset($error) ? $error : ""); ?></span>
            <input type="text" name="isbn" minlength="13" maxlength="13" value="<?php echo $libro->getisbn(); ?>"><span class="obligatorio"> *</span><br/>
            <label>Título:</label>
            <span class="obligatorio"><?php echo (isset($error) ? $error : ""); ?></span>
            <input type="text" name="titulo" value="<?php echo $libro->gettitulo(); ?>"><span class="obligatorio"> *</span><br/>
            <label>Autor:</label>
            <input type="text" name="autor" value="<?php echo $libro->getautor(); ?>"><br/>
            <label>Categoría: </label>
            <select name="select">
                <?php
                $categorias = DB::obtieneTodasCategorias(); //Obtenemos las categorías de la base de datos.
                foreach ($categorias as $c):
                    ?>
                    <option value="<?php echo $c->getid(); ?>" <?php
                    if ($libro->getcategoriaId() === $c->getid()) {
                        echo 'selected';
                    }
                    ?>><?php echo $c->getnombre(); ?></option>
                <?php endforeach; ?>
            </select><br/>
            <div>
                <label>Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" max="100" value="<?php echo number_format($libro->getprecio(),2) ?>"> €<span class="obligatorio"> *</span>
                <label>&nbsp&nbsp&nbsp&nbsp&nbsp Stock:</label>
                <input type="number" id="stock" name="stock" min="0" value="<?php echo $libro->getstock(); ?>"><br/>
                <span class="obligatorio"><?php echo (isset($error) ? $error : ""); ?></span>
            </div>
        </div>
        <div class="datos">
            <img src="uploads/<?php echo $libro->getimagen(); ?>" alt="<?php echo $libro->gettitulo(); ?>" 
                 title="<?php echo $libro->gettitulo(); ?>"><br/><br/>
            <input type="file" name="image" accept=".jpg, .jpeg, .png"/><br/><br/>
            <label>Descripción:</label><br/>
            <textarea name="descripcion" rows="10" cols="40"><?php echo $libro->getdescripcion(); ?></textarea><br/><br/>
        </div>
        <div id="footer-form">
            <p class="aviso">
                <span class="obligatorio"> * </span>los campos son obligatorios.
            </p>  
            <input type="submit" name="actualizar" value="Editar libro">
            <input type="submit" name="cancelar" value="Cancelar" id="ocultar">
        </div>
    </div>
</form>
<?php require_once 'templates/footer.php'; ?>
