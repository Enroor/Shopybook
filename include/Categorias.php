<?php

class Categorias {
    protected $id;
    protected $nombre;

    public function getid() {return $this->id; }
    public function getnombre() {return $this->nombre; }  
    
    public function __construct($row) {
        $this->id = $row['id'];
        $this->nombre = $row['nombre'];
    }
}
?>
