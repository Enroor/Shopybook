<?php
/*
* Eliminar un libro del carrito
*/
session_start();
if(!empty($_SESSION["cart"])){
	$cart  = $_SESSION["cart"];
	if(count($cart)==1){ unset($_SESSION["cart"]); }
	else{
		$newcart = array();
		foreach($cart as $c){
			if($c["id"]!=$_GET["id"]){
				$newcart[] = $c;
			}
		}
		$_SESSION["cart"] = $newcart;
	}
}
header('Location: carrito.php');

?>