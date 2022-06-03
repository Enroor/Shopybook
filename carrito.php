<?php
session_start();
require_once './include/DB.php';
require_once './templates/header.php';
require_once './include/utils.php';

$error = '';
//Si se ha mandandado el formulario de pagar.
if (isset($_POST['pagar'])) {
    //Si hay sesión del usuario redireccionamos a la pagina de pago.
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php'); //Si no ha iniciado sesión redireccionamos al login.
    }
}

//Si existe la sesión carrito es que hay productos en la cesta.
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    //Comprobamos si existe la sesión del usuario.
    if (isset($_SESSION['usuario'])) {
        //Obtenemos datos del usuario.
        $user = DB::obtieneCliente($_SESSION['usuario']);
        //Obtiene la fecha en el momento de realizar pedido.
        $fecha = date('Y-m-d', time()); 
        //Si se pulsa en realizar pago se envia el formulario.
        if (isset($_POST['finalizar-compra'])) {
            //Comprobamos que se han rellenado todos los datos del formulario.
            if (empty($_POST['nombre'])) {
                $error = '<b>Error al finalizar la compra:</b> tienes que añadir tu nombre.';
            } else if (empty($_POST['apellidos'])) {
                $error = '<b>Error al finalizar la compra:</b> tienes que añadir tus apellidos.';
            } else if (empty($_POST['direccion'])) {
                $error = '<b>Error al finalizar la compra:</b> tienes que añadir una dirección.';
            } else if (empty($_POST['tarjeta']) || !(preg_match('/[0-9]{16}/', $_POST['tarjeta']))) {
                $error = '<b>Error al finalizar la compra:</b> tienes que añadir una tarjeta válida.';
            } else if(empty($_POST['fecha-cad'] || !(preg_match('/\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01]){2}/', $_POST['fecha-cad'])))){
                $error = '<b>Error al finalizar la compra:</b> fecha de caducidad errónea.';
            }else if(empty($_POST['csv']) || !(preg_match('/[0-9]{3}/', $_POST['csv']))){
                $error = '<b>Error al finalizar la compra:</b> número csv incorrecto debe ser de 3 cifras.';
            }else{
                $total = 0;
                $nombre = limpiaTexto($_POST['nombre']);
                $apellidos = limpiaTexto($_POST['apellidos']);
                $direccion = limpiaTexto($_POST['direccion']);
                $num_tarjeta = sanearNums($_POST['tarjeta']);
                $fecha_cad = sanearNums($_POST['fecha-cad']);
                $cvs = sanearNums($_POST['csv']);
                $estado = limpiaTexto('PENDIENTE DE ENVÍO');
                //Obtenemos el usuario y su id.
                $user = DB::obtieneCliente($_SESSION['usuario']);
                $userId = $user->getid();

                //ingresar el total del pedido
                foreach ($_SESSION['cart'] as $c) {
                    $libro = DB::obtieneLibro($c['id']);
                    $total += number_format($c["cantidad"] * $libro->getprecio(), 2);
                }
                if($fecha_cad < $fecha){
                    $fecha_cad = NULL;
                }
                //Insertamos un nuevo pedido.
                $newPedido = DB::insertarPedido($fecha, $total, $nombre, $apellidos, $direccion, hash('sha512',$num_tarjeta), $fecha_cad, $cvs, $estado, $userId);
                $pedido = DB::obtienePedidosCliente($userId); //Para obtener el id del pedido
                if ($pedido) {
                    foreach ($_SESSION['cart'] as $c) {
                        //Obtenemos el libro según la id almacenada en la session carrito.
                        $libro = DB::obtieneLibro($c['id']);
                        //Insertamos nueva compra.
                        $compraRealizada = DB::insertarCompra($libro->gettitulo(), $libro->getimagen(), $c["cantidad"], $libro->getprecio() * $c['cantidad'], $libro->getid(), $pedido[0]->getid());
                        if ($compraRealizada) {
                            //Calculamos el nuevo stock del libro
                            $newStock = $libro->getstock() - $c['cantidad'];
                            //Actualizamos el stock
                            DB::actualizarStock($newStock, $libro->getid());
                            header('Location: pagar.php');
                        }
                    }
                }
        }
        }
        ?>
        <!-------------CARRITO CUANDO EL USUARIO YA ESTÁ LOGUEADO----------->
        <div id="container-cesta">
            <div class='cesta-titulo'>
                <h4>Cesta de la compra</h4>
            </div>
            <div id='error-compra'>
                <span class='obligatorio'><?php echo $error;?></span>
            </div>
            <table id="tabla-cesta">
                <thead>
                    <tr>
                        <th colspan='2'>Libro</th>
                        <th>Cantidad</th>
                        <th>Precio/ud.</th>
                        <th>Precio total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id='tbody-cesta'>
                    <?php
                    $totalPrecio = 0;
                    //Apartir de aqui hacemos el recorrido de los productos obtenidos y los reflejamos en una tabla. 
                    foreach ($_SESSION["cart"] as $c) {
                        $libro = DB::obtieneLibro($c['id']);
                        ?>
                        <tr>
                            <th class='img-cesta'><img src='uploads/<?php echo $libro->getimagen(); ?>' alt='<?php echo $libro->getimagen(); ?>' title='<?php echo $libro->getimagen(); ?>'/></th>
                            <th class='titulo-autor'>
                                <div><h4><?php echo $libro->gettitulo(); ?></h4></div>
                                <div><h6><?php echo $libro->getautor(); ?></h6></div>
                            </th>                
                            <th><?php echo $c["cantidad"]; ?></th>
                            <th class='precio'><?php echo number_format($libro->getprecio(), 2); ?> €</th>
                            <th class='total'><?php
                                $totalPrecio += number_format($c["cantidad"] * $libro->getprecio(), 2);
                                echo number_format($c["cantidad"] * $libro->getprecio(), 2);
                                ?> €</th>
                            <th>
                                <?php
                                $found = false;
                                //Botón elimnar de la cesta.
                                foreach ($_SESSION["cart"] as $c) {
                                    if ($c["id"] == $libro->getid()) {
                                        $found = true;
                                        break;
                                    }
                                }
                                ?>
                                <a href='delLibroCarrito.php?id=<?php echo $c["id"]; ?>' class='eliminar-producto-cesta'>
                                    <img src='imgs/eliminar.png' alt='eliminar' title='eliminar'>
                                </a>
                            </th>
                        </tr>
                        <?php
                    }
                    ?>
                <th></th>
                <th class='text-total'>Subtotal</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($totalPrecio, 2); ?> €</th>
                <th></th>
                </tr>
                <tr>   
                    <th></th>
                    <th class='text-total'>Gastos de envío<br/>(Gratis compra superior a 30€)</th>
                    <th></th>
                    <th></th>
                    <th id='precio-envio'><?php
                        $result;
                        ($totalPrecio > 30) ? $result = 'Gratis' : $result = '3.99 €';
                        echo $result
                        ?>
                    </th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th class='text-total'> TOTAL imp. incluidos (21%)</th>
                    <th></th>
                    <th></th>
                    <!--Si el pedido supera los 30€ gastos de envio gratis sino 3.99€-->
                    <th><?php
                        ($totalPrecio > 30) ? $totalPrecio : $totalPrecio += 3.99;
                        echo number_format($totalPrecio, 2);
                        ?> €
                    </th>
                    <th></th>
                </tr>
                </tbody>
            </table>
            <div id='botones-cesta'>
                <a href="index.php"><input type='submit' value='Seguir comprando' id='volver-tienda'/></a>
                <a href="vaciarCesta.php"><input type='submit' value='Vaciar cesta' id='vaciar'/></a>
                <a herF='carrrito.php'>
                    <input type='submit' name='pagar' value='Pagar' id="pagar"/>
                </a>
            </div>
        </div>
        <!----------FORMULARIO DE PAGO----------->
        <div id="realizar-pago">
            <div class='cesta-titulo'>
                <h4>Finalizar compra</h4>
            </div>
            <!--Formulario para realizar pago-->
            <form action="carrito.php" method="post" id="form-pago">
                <div id="form-container">
                    <input class="column-left" type="text" name="nombre" value='<?php echo $user->getNombre(); ?>' required="obligatorio">
                    <input class="column-right" type="text" name="apellidos" value='<?php echo $user->getapellido1() . ' ' . $user->getapellido2(); ?>' required="obligatorio">
                    <input class="input-field" type="text" name="direccion" value='<?php echo $user->getDireccion(); ?>' required="obligatorio">
                    <img src="imgs/tarjeta-de-credito.png" id="tarjeta" alt="tarjeta" title="tarjeta"/>
                    <input class="input-field" type="text" name="tarjeta" minlength="16" maxlength="16" size="16" required="obligatorio" placeholder="Nº tarjeta">
                    <input class="column-left" type="text" name="fecha-cad" required="obligatorio" placeholder="YYYY-MM-DD">
                    <input class="column-right" type="text" name="csv" minlength="3"' maxlength="3" size="3" required="obligatorio" placeholder="CCV">
                     <p id='precio-factura'>Precio total del pedido (21% IVA incl.) ------------------ <?php echo number_format($totalPrecio, 2); ?> €</p>
                    <div>
                        <a href='carrito.php' id='cesta'><input type="submit" value="Volver a la cesta"></a>
                        <a href='pagar.php'><input id="input-button" name="finalizar-compra" type="submit" value="Pagar"/></a>
                    </div>
                </div>   
            </form>
        </div>
        <?php
    } else {
        ?>
        <!-------Carrito de USUARIO NO REGISTRADO----->
        <div id="container-cesta">
            <div class='cesta-titulo'>
                <h4>Cesta de la compra</h4>
            </div>
            <table id="tabla-cesta">
                <thead>
                    <tr>
                        <th colspan='2'>Libro</th>
                        <th>Cantidad</th>
                        <th>Precio/ud.</th>
                        <th>Precio total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id='tbody-cesta'>
                    <?php
                    $totalPrecio = 0;
                    //Apartir de aqui hacemos el recorrido de los productos obtenidos y los reflejamos en una tabla. 
                    foreach ($_SESSION["cart"] as $c) {
                        $libro = DB::obtieneLibro($c['id']);
                        ?>
                        <tr>
                            <th class='img-cesta'><img src='uploads/<?php echo $libro->getimagen(); ?>' alt='<?php echo $libro->getimagen(); ?>' title='<?php echo $libro->getimagen(); ?>'/></th>
                            <th class='titulo-autor'>
                                <div><h4><?php echo $libro->gettitulo(); ?></h4></div>
                                <div><h6><?php echo $libro->getautor(); ?></h6></div>
                            </th>                
                            <th><?php echo $c["cantidad"]; ?></th>
                            <th class='precio'><?php echo number_format($libro->getprecio(), 2); ?> €</th>
                            <th class='total'><?php
                                $totalPrecio += number_format($c["cantidad"] * $libro->getprecio(), 2);
                                echo number_format($c["cantidad"] * $libro->getprecio(), 2);
                                ?> €</th>
                            <th>
                                <?php
                                $found = false;
                                //Botón elimnar de la cesta.
                                foreach ($_SESSION["cart"] as $c) {
                                    if ($c["id"] == $libro->getid()) {
                                        $found = true;
                                        break;
                                    }
                                }
                                ?>
                                <a href='delLibroCarrito.php?id=<?php echo $c["id"]; ?>' class='eliminar-producto-cesta'>
                                    <img src='imgs/eliminar.png' alt='eliminar' title='eliminar'>
                                </a>
                            </th>
                        </tr>
                        <?php
                    }
                    ?>
                <th></th>
                <th class='text-total'>Subtotal</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($totalPrecio, 2); ?> €</th>
                <th></th>
                </tr>
                <tr>   
                    <th></th>
                    <th class='text-total'>Gastos de envío<br/>(Gratis compra superior a 30€)</th>
                    <th></th>
                    <th></th>
                    <th id='precio-envio'><?php
                        $result;
                        ($totalPrecio > 30) ? $result = 'Gratis' : $result = '3.99 €';
                        echo $result
                        ?>
                    </th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th class='text-total'> TOTAL imp. incluidos (21%)</th>
                    <th></th>
                    <th></th>
                    <!--Si el pedido supera los 30€ gastos de envio gratis sino 3.99€-->
                    <th><?php
                        ($totalPrecio > 30) ? $totalPrecio : $totalPrecio += 3.99;
                        echo number_format($totalPrecio, 2);
                        ?> €
                    </th>
                    <th></th>
                </tr>
                </tbody>
            </table>
            <div id='botones-cesta'>
                <a href="index.php"><input type='submit' value='Seguir comprando' id='volver-tienda'/></a>
                <a href="vaciarCesta.php"><input type='submit' value='Vaciar cesta' id='vaciar'/></a>
                <a href="login.php"><input type='submit' name='pagar' value='Pagar' id="pagar"/></a>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class='cesta-titulo'>
        <h4 id='cesta-vacía'>Cesta de la compra vacía</h4>
    </div>
    <?php
}
?>
<?php require_once './templates/footer.php'; ?>