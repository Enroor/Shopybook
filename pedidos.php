<?php
session_start();
require_once 'templates/header.php';
require_once('include/DB.php');

/* * *******PAGINAR********* */
$totalPedidos = DB::totalPedidos(); //Total de pedidos para paginar
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

//El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * 8;

$pedidos = DB::obtienePedidos($offset); //Obtenemos los pedidos de la base de datos.
if (isset($_POST['guardar'])) {
    $estado = $_POST['select'];
    $id = $_GET['id'];
    var_dump($estado);
    var_dump(($id));
    $actualizaEstado = DB::actualizarEstadoPedido($id, $estado);
}
?>
<!-- TABLA PARA MOSTAR LOS LIBROS -->
<table id='tabla-pedidos'>
    <thead>
        <tr>
            <th>Nº Pedido</th>
            <th>ID Usuario</th>
            <th>Nombre completo</th>
            <th>Fecha de compra</th>
            <th>Dirección</th>
            <th>Total</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <!--TABLA PARA MOSTRAR LOS PEDIDOS DEL CLIENTE PANEL ADMIN-->
        <?php
        //Si se pulsa en modificar
        if (isset($_POST['modificar'])) {
            $id = $_GET['id']; //Recogemos el id de la url
            $idPedido = $_GET['idPedido']; //Recogemos el idPedido de la url
            
            if (isset($_POST['select'])) {
                $estado = $_POST['select'];
                //Actualizamos el estado del pedido.
                $pedido = DB::actualizarEstadoPedido($id, $estado, $idPedido);
                if($pedido){
                    header('Location: pedidos.php');
                }
            }
        }
        foreach ($pedidos as $p):
            ?>     
            <tr>
                <td><?php echo $p->getid(); ?></td>
                <td><?php echo $p->getusuarioId(); ?></td>
                <td><?php echo $p->getnombre() . ' ' . $p->getapellidos(); ?></td>  
                <td><?php echo $p->getfecha(); ?></td>
                <td><?php echo $p->getdireccion(); ?></td> 
                <td><?php echo number_format($p->gettotal(), 2); ?>€</td>
                <td>
                    <form action='pedidos.php?id=<?php echo $p->getusuarioId(); ?>&idPedido=<?php echo $p->getid(); ?>' method='post'>
                        <select name="select" id='select-pedidos'>
                            <option value="CONFIRMADO" <?php if ($p->getestado() === 'CONFIRMADO') echo 'selected'; ?>>
                                CONFIRMADO
                            </option>
                            <option value="PENDIENTE DE ENVIO" <?php if ($p->getestado() === 'PENDIENTE DE ENVIO') echo 'selected'; ?>>
                                PENDIENTE DE ENVÍO
                            </option>
                            <option value="EN CAMINO" <?php if ($p->getestado() === 'EN CAMINO') echo 'selected'; ?>>
                                EN CAMINO
                            </option>
                            <option value="ENTREGADO" <?php if ($p->getestado() === 'ENTREGADO') echo 'selected'; ?>>
                                ENTREGADO
                            </option>
                        </select>
                        <div id="footer-form-pedidos">
                            <a><input type="submit" name="modificar" value="Modificar" class='modifica-estado'></a>
                        </div>
                    </form>
                </td>
            </tr>    
        <?php endforeach; ?>
    </tbody>
</table>
<?php
//Para obtener las páginas dividimos el total entre los productos por página, y redondeamos hacia arriba
$paginas = ceil($totalPedidos[0] / 8);
if (isset($_POST['palabraClave']))
    
    ?>
<div class="row">
    <div>
        <ul class="pagination">
            <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
            <?php if ($pagina > 1) { ?>
                <li>
                    <a href="pedidos.php?pagina=<?php echo $pagina - 1 ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
            <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                <li class="<?php if ($x == $pagina) echo "active" ?>">
                    <a href="pedidos.php?pagina=<?php echo $x ?>">
                        <?php echo $x ?></a>
                </li>
            <?php } ?>
            <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
            <?php if ($pagina < $paginas) { ?>
                <li>
                    <a href="pedidos.php?pagina=<?php echo $pagina + 1 ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="row" id="page-actual">
        <div> 
            <p>Página <b><?php echo $pagina; ?></b> de <?php echo $paginas; ?> </p>
            <p>Total de pedidos: <b> <?php echo $totalPedidos[0]; ?></b></p>
        </div>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>