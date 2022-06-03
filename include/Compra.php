<?php
require_once('DB.php');

class Compra {
    protected $id;
    protected $titulo;
    protected $imagen;
    protected $cantidad;
    protected $precio;
    protected $libroID;
    protected $pedidoID;
    
    public function getid() {return $this->id; }
    public function gettitulo() {return $this->titulo; }
    public function getimagen() {return $this->imagen; }
    public function getcantidad() {return $this->cantidad; }
    public function getprecio() {return $this->precio; }    
    public function getlibroId() {return $this->libroID; } 
    public function getpedidoId() {return $this->pedidoID; } 
    
    public function __construct($row) {
        $this->id = $row['id'];
        $this->titulo = $row['titulo'];
        $this->imagen = $row['imagen'];
        $this->cantidad = $row['cantidad'];
        $this->precio = $row['precio'];
        $this->libroID= $row['libroID'];
        $this->pedidoID = $row['pedidoID'];
    }
}

?>
