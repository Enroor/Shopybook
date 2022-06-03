<?php
/*
* Agrega el libro a la variable de sesion de libro.
*/
session_start();
if(!empty($_POST)){
    //Obtenemos el id del libro y cantidad
	if(isset($_GET["id"]) && isset($_POST["cantidad"])){
		// si es el primer libro lo agregamos
		if(empty($_SESSION["cart"])){
			$_SESSION["cart"]=array( array("id"=>$_GET["id"],"cantidad"=> $_POST["cantidad"]));
		}else{
			// a partir del segundo libro:
			$cart = $_SESSION["cart"];
			$repeated = false;
			// recorremos el carrito en busqueda del libro repetido
			foreach ($cart as $c) {
				// si el libro esta repetido paramos el bucle
				if($c["id"]==$_GET["id"]){
					$repeated=true;
					break;
				}
			}
			// si el libro es repetido no hacemos nada, simplemente redirigimos
			if($repeated){
				echo "Error: Producto Repetido!";
			}else{
				// si el producto no esta repetido entonces lo agregamos a la variable cart y despues asignamos la variable cart a la variable de sesion
				array_push($cart, array("id"=>$_GET["id"],"cantidad"=> $_POST["cantidad"]));
				$_SESSION["cart"] = $cart;
			}
		}
                if(isset($_GET['categoria'])){
                    header('Location: librosCategorias.php');
                }else{
                    header('Location: index.php');
                }
	}
}

?>