<?php

class User {
    protected $id;
    protected $usuario;
    protected $passwd;
    protected $dni;
    protected $nombre;
    protected $apellido_1;
    protected $apellido_2;
    protected $direccion;
    protected $imagen_perfil;
    protected $telefono;
    
    public function getid() {return $this->id; }
    public function getusuario() {return $this->usuario; }
    public function getpasswd() {return $this->passwd; }
    public function getdni() {return $this->dni; }
    public function getnombre() {return $this->nombre; }
    public function getapellido1() {return $this->apellido_1; }
    public function getapellido2() {return $this->apellido_2; }
    public function getdireccion() {return $this->direccion; }
    public function getimagen_perfil() {return $this->image_perfil; }
    public function gettelefono() {return $this->telefono; }    
    
    public function __construct($row) {
        $this->id = $row['id'];
        $this->usuario = $row['usuario'];
        $this->passwd = $row['contrasena'];
        $this->dni = $row['dni'];
        $this->nombre = $row['nombre'];
        $this->apellido_1 = $row['apellido_1'];
        $this->apellido_2 = $row['apellido_2'];
        $this->direccion = $row['direccion'];
        $this->imagen_perfil= $row['imagen_perfil'];
        $this->telefono = $row['telefono'];
    }
}
?>