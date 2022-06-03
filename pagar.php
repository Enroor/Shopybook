<?php
session_start();
unset($_SESSION["cart"]);
require_once './templates/header.php';
require_once './include/DB.php';

$nomUser = $_SESSION['usuario']; //nombre usuario
$user = DB::obtieneCliente($nomUser);
$idPedido = DB::obtienePedidosCliente($user->getid());
?>
<div id='compra-realizada'>
    <div>
        <h1>Compra realizada :)</h1>
    </div>
    <div id='factura'>
        <div>
            <h5>Factura</h5>
            <p id='fecha-factura'>
                Shopybook - Compra de libros online<br/>
                Pedido número <?php echo substr(str_repeat(0, 9) . $idPedido[0]->getid(), - 9); ?><br/>
                Realizado el <?php echo date('d-m-Y', time()); ?>
            </p>
            <p id='productos'>
                <?php
                $totalPrecio = 0;
                $compra = DB::obtieneCompra($idPedido[0]->getid());
                //Apartir de aqui hacemos el recorrido de los productos obtenidos por la compra y los reflejamos en una tabla. 
                foreach ($compra as $c) {
                    $libro = DB::obtieneLibro($c->getlibroId());
                    ?>

                    <?php echo '<b>Libro</b> &nbsp;&nbsp;'.$libro->gettitulo().'<br/>'; ?>
                    <?php echo '<b>Cantidad</b> &nbsp;&nbsp;' . $c->getcantidad().'<br/>'; ?>
                    <?php echo '<b>Precio/ud</b> &nbsp;&nbsp;' . number_format($libro->getprecio(), 2); ?> € <br/>
                    <?php
                    $totalPrecio += number_format($c->getcantidad() * $libro->getprecio(), 2);
                    echo '<b>Total</b> &nbsp;&nbsp;' . number_format($c->getcantidad() * $libro->getprecio(), 2);
                    ?> €<br/><br/>
                    <?php
                }
                ?>
            </p>
            <p id='total'>
            Subtotal-----------------------------------------------------------
            <?php echo number_format($totalPrecio, 2); ?> €<br/>
            Gastos de envío<br/>(Gratis compra superior a 30€)-------------------------------
            <?php
            $result='Gratis';
            ($totalPrecio > 30) ? $result  : $result = '3.99 €';
            echo $result
            ?><br/>
            TOTAL imp. incluidos (21%)----------------------------------
            <!--Si el pedido supera los 30€ gastos de envio gratis sino 3.99€-->
            <?php
            ($totalPrecio > 30) ? $totalPrecio : $totalPrecio += 3.99;
            echo number_format($totalPrecio, 2);
            ?> €
            </p>
        </div>
    </div>
    <div>
        <a href="index.php"><input type='submit' value='Seguir comprando' id='volver-tienda'/></a>
    </div>
</div>

<?php require_once './templates/footer.php'; ?>
