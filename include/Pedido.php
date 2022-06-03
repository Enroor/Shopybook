<?php

class Pedido {
    protected $id;
    protected $fecha;
    protected $total;
    protected $nombre;
    protected $direccion;
    protected $estado;
    protected $usuarioid;
    
    public function getid() {return $this->id; }
    public function getfecha() {return $this->fecha; }
    public function gettotal() {return $this->total; }
    public function getnombre() {return $this->nombre; }
    public function getapellidos() {return $this->apellidos; }
    public function getdireccion() {return $this->direccion; }
    public function getestado() {return $this->estado; } 
    public function getusuarioId() {return $this->usuarioId; } 
    
    public function __construct($row) {
        $this->id = $row['id'];
        $this->fecha = $row['fecha'];
        $this->total = $row['total'];
        $this->nombre = $row['nombre'];
        $this->apellidos = $row['apellidos'];
        $this->direccion = $row['direccion'];
        $this->estado = $row['estado'];
        $this->usuarioId = $row['usuarioID'];
    }
}
?>