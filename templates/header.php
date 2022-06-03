<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Shopybook</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Enrique Rodríguez Ortega">
        <link rel="icon" type="image/png" href="./imgs/favicon.png" />
        <link href="./css/style.css" rel="stylesheet" type="text/css">
        <link href="./css/header.css" rel="stylesheet" type="text/css">
        <link href="./css/menu.css" rel="stylesheet" type="text/css">
        <link href="./css/libros.css" rel="stylesheet" type="text/css">
        <link href="./css/libro.css" rel="stylesheet" type="text/css">
        <link href="./css/contacto.css" rel="stylesheet" type="text/css">
        <link href="./css/perfil.css" rel="stylesheet" type="text/css">
        <link href="./css/cesta.css" rel="stylesheet" type="text/css">
        <link href="./css/footer.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!--codigo HTML -->
        <header>
            <div id="container-header">
                <!--Logo de la página-->
                <div>
                    <a href="index.php"><img id="logo" src="imgs/logo.png" alt="logo" title='Shopybook'/></a>
                </div>
                <!--Buscador de libros-->
                <div id="buscador">
                    <form method='post'>
                        <input id="search" name="palabraClave" type="text" size="40" placeholder="Busca por título o autor">
                    </form>
                </div>
                <?php
                if (!isset($_SESSION['usuario'])) {
                    ?>
                    <!--Si no está logueado el usuario, mostramos para que se logué.-->
                    <div class="user">
                        <img class="icon_usser" src="imgs/usuario.png" alt="icono usuario"/>
                        <a href="login.php">
                            <div id='iniciar-sesion'>
                                <input type='submit' id='iniciar_sesion' name='enviar' value='Iniciar sesión' />
                            </div>
                        </a>
                    </div>
                    <?php
                } else {
                    ?>
                    <!--Logueado el usuario, mostramos su nombre y botón cerrar sesión.-->
                    <div class="user">
                        <div>
                            <a href="perfil.php"><img class="icon_usser" src="imgs/usuario.png" alt="icono usuario"/></a>
                            <label>Hola, <?php echo $_SESSION['usuario'] ?></label>
                        </div>      
                        <a href="logout.php"><input type="submit" name="cerrar_sesion" value='Cerrar sesión' id="logout"/></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </header>
        <!--BARRA DE MENU SEGUN ROL USUARIO-->
        <nav>
            <div id="container-menu">
                <ul>
                    <?php
                    if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'admin') {
                        ?>
                        <li><a href="index.php">INICIO</a></li>
                        <li><a href="adminLibros.php">LIBROS</a></li>
                        <li><a href="categorias.php">CATEGORÍAS</a></li>
                        <li><a href="pedidos.php">PEDIDOS</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="index.php">INICIO</a></li>
                        <li><a href="librosCategorias.php">CATEGORÍAS</a></li>
                        <li><a href="contacto.php">CONTACTO</a></li>
                        <li><a href="acercaDe.php">ACERCA DE</a></li>
                        <li id="container-carrito">
                            <a href="carrito.php" id="carrito"><img src="imgs/carrito.png" id="carrito-compra" alt="carrito compra"/>
                                <span id="carrito-num"><?php $result = 0; (isset($_SESSION['cart']))? $result=count($_SESSION['cart']) : $result; echo $result;?></span></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <main>

