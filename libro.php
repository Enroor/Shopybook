<?php
session_start();
require_once './templates/header.php';
require_once './include/DB.php';

//Obtenemos el libro según la id pasada por la url.
$libro = DB::obtieneLibro($_GET['id']);
?>
<section id="libro">
    <div id="left">
        <img src="uploads/<?php echo $libro->getimagen(); ?>" alt="<?php echo $libro->getimagen(); ?>" title="<?php echo $libro->getimagen(); ?>"/>
        <!------- RANTING DE LOS LIBROS--------
        <form action="#" method="post">
            <div class="star_content">
                <input name="rate" value="1" type="radio" class="star"/> 
                <input name="rate" value="2" type="radio" class="star"/> 
                <input name="rate" value="3" type="radio" class="star"/> 
                <input name="rate" value="4" type="radio" class="star" checked="checked"/> 
                <input name="rate" value="5" type="radio" class="star"/>
            </div>
            <button type="submit" name="submitRatingStar" id="submitRatingStar" class="btn btn-primary btn-sm">Enviar</button>
        </form>
        -->
    </div>
    <div id="center">
        <h2><?php echo $libro->gettitulo(); ?></h2>
        <h4><?php echo $libro->getautor(); ?></h4>
        <span class='categoria-libro'><?php $categoria = DB::obtieneCategoriaId($libro->getcategoriaId());
            echo '<i>' . $categoria->getnombre() . '</i>'; ?></span>
        <p id="price"><?php echo number_format($libro->getprecio(), 2) . ' €'; ?></p>
        <div>
            <?php
            //Cuando el stock sea 0 o null mostramos Stock no disponible.
            if ($libro->getstock() === 0 || $libro->getstock() === NULL) {
                ?>
                <span id="sin-stock">Stock no disponible.</span>
                <?php
            } else {
                ?>
                <form method="post" action='addCarrito.php?id=<?php echo $libro->getid(); ?>'>
                    <span>Stock:</span>
                    <input type="number" min="1" max='<?php echo $libro->getstock();?>' name="cantidad" placeholder='1'/>
                    <span>Añadir al carrito</span>
                    <button>
                        <a href='addCarrito.php?id=<?php echo $libro->getid(); ?>'><img src="./imgs/anadir.png" alt="btn-anadir-carrito" title="Añadir al carrito"/></a>
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
        <p><b>ISBN:&nbsp; &nbsp; </b> <?php echo $libro->getisbn();?></p>
        <p><u><b>Sinopsis:</b></u></p>
        <p>
            <?php echo $libro->getdescripcion(); ?>
        </p>
    </div>
    <div id="right">
        <div>
            <p>Envío gratis a partir de <b>30.00 €</b></p>
        </div>
    </div>
</section>
<?php require_once './templates/footer.php'; ?>
