<?php
session_start();
require_once'./templates/header.php';
require_once('include/DB.php');
require_once('include/utils.php');

//Comprobamos si está la sesión usuario
if (isset($_SESSION['usuario'])) {
    //Obtenemos los datos del cliente
    $cliente = DB::obtieneCliente($_SESSION['usuario']);
    $oldpasswd = $cliente->getpasswd();
} else {
    header("Location: login.php");
}
$regexDni = '/([0-9]{8}[A-Z]{1})/';
$regexNie = '/([XYZ][0-9]{7}[A-Z])/';
$regexCif = '/([AB][0-9]{9})/';
//Comprobamos si se ha mandando el formulario para modificar los datos
if (isset($_POST['guardar'])) {
    //comprobamos si el nombre de usuario está vacío o no declarado.
    if (!(isset($_POST['usuario'])) || empty($_POST['usuario'])) {
        $error = "El usuario es obligatorio";
    } elseif(!empty($_POST['dni']) && !(preg_match($regexDni, $_POST['dni']) || preg_match($regexNie, $_POST['dni']) || preg_match($regexCif, $_POST['dni']))){
        $error = 'Error: el DNI/NIE/CIF no es válido.';
    }elseif(!(preg_match('/([679][0-9]{8})/', $_POST['telefono']))){
        $error = 'Error: número de teléfono no válido.';
    }else{
        $id = $cliente->getid();
        //Filtramos los datos pasados por el input.
        $usuario = limpiaTexto($_POST['usuario']);
        $passwd = limpiaTexto($_POST['password']);
        $nombre = limpiaTexto($_POST['nombre']);
        $apellido1 = limpiaTexto($_POST['apellido1']);
        $apellido2 = limpiaTexto($_POST['apellido2']);
        $dni = limpiaTexto($_POST['dni']);
        $direccion = limpiaTexto($_POST['direccion']);
        $telefono = sanearNums($_POST['telefono']);
        $imagen = NULL;

        if (empty($passwd)) {
            $passwd = $oldpasswd;
        } else {
            $passwd = hash('sha512', $passwd);
        }
        //Actualizamos los datos del usuario
        $datos = DB::actualizarDatosCliente($id, $usuario, $passwd, $dni, $nombre, $apellido1, $apellido2, $direccion, $imagen, $telefono);
        if ($datos) {
            echo 'Datos modificados correctamente';
            $_SESSION['usuario'] = $_POST['usuario'];
            header('Location: perfil.php');
        } else {
            echo 'Fallo en la modificación de datos';
        }
    }
}
?>
<div id="perfil-container">
    <!--MENU LATERAL-->
    <div id="menu-perfil">
        <ul>
            <!--Cuando seleccionamos un enlace mostramos su contenido y ocultamos el resto jquery: js/perfil.js-->
            <li><a href="#datos-perfil" onclick="return()" id="perfil">Perfíl</a></li>
            <?php
            //Si el usuario es el administrador no parece los pedidos de compra. Sólom pueden comprar los clientes.
            if (!($_SESSION['usuario'] === 'admin')) {
                ?>
                <li><a href="#datos-pedido" onclick="return()" id="pedidos">Pedidos</a></li>
                <?php
            }
            ?>
            <li><a href="#modificar-perfil" onclick="return()" id="modifica-perfil">Modificar perfíl</a></li>
        </ul>
    </div>
    <!--OBTENEMOS LOS DATOS ACTUALES DEL USUARIO-->
    <div id="datos-perfil" name="datos-perfil">
        <div>
            <!--Obtenemos los datos del usuario-->
            <table id='tabla-perfil'>
                <tbody>
                    <tr>
                        <th>Nombre de usuario</th>
                        <th><?php echo $cliente->getusuario(); ?></th>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <th><?php echo $cliente->getnombre(); ?></th>
                    </tr>
                    <tr>
                        <th>Primer apellido</th>
                        <th><?php echo $cliente->getapellido1(); ?></th>
                    </tr>
                    <tr>
                        <th>Segundo apellido</th> 
                        <th><?php echo $cliente->getapellido2(); ?></th> 
                    </tr>
                    <tr>
                        <th>DNI/NIE/CIF</th>
                        <th><?php echo $cliente->getdni(); ?></th>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <th><?php echo $cliente->getdireccion(); ?></th>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <th><?php echo $cliente->gettelefono(); ?></th>
                    </tr>
                    </tr>    
                </tbody>
            </table>
        </div>
    </div>
    <!--TABLA PARA MOSTRAR LOS PEDIDOS DEL CLIENTE-->
    <div id="datos-pedido" name="datos-pedido">
        <!-- TABLA PARA MOSTAR LOS PEDIDOS -->
        <table id="tabla-pedidos-user">
            <thead>
                <tr>
                    <th>Nº Pedido</th>
                    <th>Fecha de compra</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pedidos = DB::obtienePedidosCliente($cliente->getid()); //Obtenemos los pedidos según el id del cliente.
                foreach ($pedidos as $p) {
                    ?>     
                    <tr>
                        <td><?php echo $p->getid(); ?></td>
                        <td><?php echo $p->getfecha(); ?></td>
                        <td><?php echo $p->getnombre() . ' ' . $p->getapellidos(); ?></td>  
                        <td><?php echo $p->getdireccion(); ?></td> 
                        <td><?php echo number_format($p->gettotal(), 2); ?>€</td>
                        <td>
                            <?php echo $p->getestado(); ?> 
                        </td>
                    </tr>    
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="modificar-perfil" name="modificar-perfil">
        <!--FORMULARIO PARA MODIFICAR LOS DATOS DEL PERFIL DEL USUARIO-->
        <form id="actualizar-form-perfil" action="perfil.php" method="post">
            <div id="container-datos-perfil">
                <div class="datos" id='datos-perfil'>
                    <label><b>Usuario:</b></label>
                    <?php
                    //============================================================
                    //Si el usuario es distinto al ADMIN puede cambiar el nombre de usuario.
                    //============================================================
                    if (!($_SESSION['usuario'] === 'admin')) {
                        ?>
                        <input type="text" name="usuario" value="<?php echo $cliente->getusuario(); ?>"><span class="obligatorio"> *</span><br>
                        <?php
                    } else {
                        ?>
                        <input type="hidden" name="usuario" value="<?php echo $cliente->getusuario(); ?>"> <span><?php echo $cliente->getusuario(); ?></span><br/>
                        <?php
                    }
                    ?>
                    <label><b>Contraseña:</b></label>
                    <input type="password" name="password"><br>
                    <label><b>Nombre:</b></label>
                    <input type="text" name="nombre" value="<?php echo $cliente->getnombre(); ?>"><br/>
                    <label><b>Primer apellido:</b></label>
                    <input type="text" name="apellido1" value="<?php echo $cliente->getapellido1(); ?>"><br/>
                    <label><b>Segundo apellido:</b></label>
                    <input type="text" name="apellido2" value="<?php echo $cliente->getapellido2(); ?>"><br/>
                    <label><b>DNI/NIE/CIF</b></label>
                    <input type="text" name="dni" value="<?php echo $cliente->getdni(); ?>"><br/>
                    <div>
                        <label><b>Dirección:</b></label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo $cliente->getdireccion(); ?>"><br/>
                        <label><b>Teléfono:</b></label>
                        <input type="number" id="telefono" name="telefono" value="<?php echo $cliente->gettelefono(); ?>"><br/>
                    </div>
                </div>
                <div id="footer-form-perfil">
                    <p class="aviso">
                        <span class="obligatorio"> * </span>los campos son obligatorios.
                    </p>  
                    <input type="submit" name="guardar" value="Guardar">
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once'./templates/footer.php'; ?>