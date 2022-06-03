<?php

class Libro {
    protected $id;
    protected $isbn;
    protected $titulo;
    protected $autor;
    protected $imagen;
    protected $descripcion;
    protected $valoracion;
    protected $stock;
    protected $precio;
    protected $categoriaID;
    
    public function getid() {return $this->id; }
    public function getisbn() {return $this->isbn; }
    public function gettitulo() {return $this->titulo; }
    public function getautor() {return $this->autor; }
    public function getimagen() {return $this->imagen; }
    public function getdescripcion() {return $this->descripcion; }
    public function getvaloracion() {return $this->valoracion; }
    public function getstock() {return $this->stock; }
    public function getprecio() {return $this->precio; }    
    public function getcategoriaId() {return $this->categoriaID; } 
    
    public function __construct($row) {
        $this->id = $row['id'];
        $this->isbn = $row['isbn'];
        $this->titulo = $row['titulo'];
        $this->autor = $row['autor'];
        $this->imagen = $row['imagen'];
        $this->descripcion = $row['descripcion'];
        $this->valoracion = $row['valoracion'];
        $this->stock = $row['stock'];
        $this->precio = $row['precio'];
        $this->categoriaID = $row['categoriaID'];
    }
}
?>
