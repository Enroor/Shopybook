<?php
require_once('config/settings.php');
require_once('Libro.php');
require_once('User.php');
require_once('Pedido.php');
require_once('Categorias.php');
require_once('Compra.php');

class DB {

    protected static function ejecutaConsulta($sql) {
        try {
            $pdoConn = new PDO(DB_DSN, DB_USER, DB_PASSWD,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $resultado = null;
            if (isset($pdoConn))
                $resultado = $pdoConn->query($sql);
        } catch (PDOException $e) {
            //Se capturan los mensajes de error.
            ("Error: " . $e->getMessage());
        }
        return $resultado;
    }

    //FUNCIÓN PARA BUSCAR LIBROS EN EL BUSCADOR 
    public static function buscador($aKeyword) {
        $sql = "SELECT * FROM libro WHERE titulo like '%" . $aKeyword . "%' OR autor like '%" . $aKeyword . "%'";
        $resultado = self::ejecutaConsulta($sql);
        $libro = NULL;

        if (isset($resultado)) {
            $row = $resultado->fetch();
            if(is_array($row))
                $libro = new Libro($row);
        }
        return $libro;
    }

    /* ****************************************************************************
     * FUNCIONES SQL PARA OBTENER TODOS LOS LIBROS O EL LIBRO EN EL PANEL CLIENTE
     * ************************************************************************** */

    public static function totalLibros() {
        $sql = "SELECT COUNT(*) FROM libro;";
        $resultado = self::ejecutaConsulta($sql);
        if (isset($resultado)) {
            $row = $resultado->fetch();
        }
        return $row;
    }

    public static function obtieneLibros($offset) {
        $sql = "SELECT * FROM libro ORDER BY id DESC LIMIT 8 OFFSET $offset;";
        $resultado = self::ejecutaConsulta($sql);
        $libros = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $libros[] = new Libro($row);
                $row = $resultado->fetch();
            }
        }
        return $libros;
    }

    public static function obtieneLibro($id) {
        $sql = "SELECT * FROM libro WHERE id=$id;";
        $resultado = self::ejecutaConsulta($sql);
        $libro = null;

        if (isset($resultado)) {
            $row = $resultado->fetch();
            $libro = new Libro($row);
        }

        return $libro;
    }
    
    public static function obtieneLibrosCategoria($id) {
        $sql = "SELECT * FROM libro WHERE categoriaID=$id;";
        $resultado = self::ejecutaConsulta($sql);
        $libros = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $libros[] = new Libro($row);
                $row = $resultado->fetch();
            }
        }
        return $libros;
    }

    /*     * *************************************************************************
     * FUNCIONES SQL PARA AÑADIR, BORRAR O ACTUALIZAR LIBROS EN EL PANEL DEL ADMIN
     * ************************************************************************* */

    public static function insertarLibro($isbn, $titulo, $autor, $precio, $stock, $image, $descripcion, $categoriaID) {
        $sql = "INSERT INTO libro (isbn, titulo, autor, precio, stock, imagen, descripcion, categoriaID)"
                . " VALUES('$isbn', '$titulo', '$autor', '$precio', '$stock', '$image', '$descripcion','$categoriaID');";

        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    public static function borrarLibro($id) {
        $sql = "DELETE FROM libro WHERE id='$id'";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    public static function actualizarLibro($id, $isbn, $titulo, $autor, $precio, $stock, $image, $descripcion, $categoriaId) {
        $sql = "UPDATE libro SET isbn='$isbn',titulo='$titulo',autor='$autor',
             imagen='$image',descripcion='$descripcion',stock='$stock',
             precio='$precio', categoriaID='$categoriaId' WHERE id='$id';";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }
    /* *********************************************************
     * FUNCIONES SQL PARA OBTENER DATOS DEL PEDIDO DE LOS CLIENTES PANEL ADMIN  E INSERTAR PANEL CLIENTE
     * ********************************************************* */

    //FUNCIÓN QUE INSERTA UN NUEVO PEDIDO
    public static function insertarPedido($fecha, $total, $nombre, $apellidos, $direccion, $num_tarjeta, $fecha_cad, $cvs, $estado, $usuarioID) {
        $sql = "INSERT INTO pedido (fecha, total, nombre, apellidos, direccion, num_tarjeta, fecha_cad, cvs, estado, usuarioID)"
                . " VALUES('$fecha', '$total', '$nombre', '$apellidos', '$direccion', '$num_tarjeta', '$fecha_cad', '$cvs', '$estado', '$usuarioID');";

        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }
    //FUNCIÓN PARA OBTENER TODOS LOS PEDIDOS DE LOS CLIENTES PARA EL PANEL DEL ADMIN
    public static function obtienePedidos() {
        $sql = "SELECT * FROM pedido ORDER BY fecha DESC;";
        $resultado = self::ejecutaConsulta($sql);
        $pedidos = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $pedidos[] = new Pedido($row);
                $row = $resultado->fetch();
            }
        }
        return $pedidos;
    }

    public static function actualizarEstadoPedido($id, $estado, $idPedido) {
        $sql = "UPDATE pedido SET estado='$estado' WHERE usuarioID='$id' AND id=$idPedido;";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    /* *********************************************************
     * FUNCIONES SQL PARA OBTENER DATOS DEL CLIENTE EN SU PERFIL
     * ********************************************************* */

    //Función para obtener los datos del cliente en su perfil.
    public static function obtieneCliente($user) {

        $sql = "SELECT * FROM usuarios WHERE usuario='$user';";
        $resultado = self::ejecutaConsulta($sql);
        $user = null;
        if (isset($resultado)) {
            $row = $resultado->fetch();
            $user = new User($row);
        }
        return $user;
    }
    public static function totalPedidos() {
        $sql = "SELECT COUNT(*) FROM pedido;";
        $resultado = self::ejecutaConsulta($sql);
         if (isset($resultado)) {
            $row = $resultado->fetch();
        }
        return $row;
    }

    //Función para obtener los pedidos del cliente según su id.
    public static function obtienePedidosCliente($id) {
        $sql = "SELECT * FROM pedido WHERE usuarioID=$id ORDER BY id DESC;";
        $resultado = self::ejecutaConsulta($sql);
        $pedidos = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $pedidos[] = new Pedido($row);
                $row = $resultado->fetch();
            }
        }
        return $pedidos;
    }
    
    //Función para actulizar los datos del cliente en su perfil de usuario.
    public static function actualizarDatosCliente($id, $usuario, $passwd, $dni, $nombre, $apellido_1, $apellido_2, $direccion, $imagen_perfil, $telefono) {
        $sql = "UPDATE usuarios SET usuario='$usuario', contrasena='$passwd',dni='$dni', nombre='$nombre', apellido_1='$apellido_1', apellido_2='$apellido_2',
             direccion='$direccion', imagen_perfil='$imagen_perfil', telefono='$telefono' WHERE id='$id';";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    /*     * ********************************************************
     * FUNCIONES SQL PARA VERIFICAR LOGIN Y REGISTRO DE USUARIO
     * ********************************************************* */

    public static function verificaUsuario($user, $passwd) {
        $passwdHash = hash('sha512', $passwd);
        $sql = "SELECT usuario FROM `usuarios` WHERE `usuario` = BINARY '$user' AND contrasena = BINARY '$passwd' OR contrasena = '$passwdHash';";
        $resultado = self::ejecutaConsulta($sql);
        $verificado = false;

        if (isset($resultado)) {
            $fila = $resultado->fetch();
            if ($fila !== false) {
                $verificado = true;
            }
        }
        return $verificado;
    }

    public static function insertarUsuario($usuario, $passwd) {
        $passwd = hash('sha512', $passwd);
        $sql = "INSERT INTO usuarios (usuario, contrasena, dni, nombre, apellido_1, apellido_2, direccion, imagen_perfil, telefono)"
                . " VALUES('$usuario','$passwd','','','','','','','');";

        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    /* ***********************************
     * FUNCIONES SQL PARA LAS CATEGORÍAS
     * *********************************** */

    //Función para paginar las ctaegorias
    public static function obtieneCategorias($offset) {
        $sql = "SELECT * FROM categoria ORDER BY nombre ASC LIMIT 8 OFFSET $offset;";
        $resultado = self::ejecutaConsulta($sql);
        $libros = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $libros[] = new Categorias($row);
                $row = $resultado->fetch();
            }
        }
        return $libros;
    }

    public static function obtieneTodasCategorias() {
        $sql = "SELECT * FROM categoria ORDER BY nombre ASC;";
        $resultado = self::ejecutaConsulta($sql);
        $categorias = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $categorias [] = new Categorias($row);
                $row = $resultado->fetch();
            }
        }
        return $categorias ;
    }

    public static function totalCategorias() {
        $sql = "SELECT COUNT(*) FROM categoria;";
        $resultado = self::ejecutaConsulta($sql);
        if (isset($resultado)) {
            $row = $resultado->fetch();
        }
        return $row;
    }

    public static function obtieneCategoriaId($id) {

        $sql = "SELECT * FROM categoria WHERE id=$id;";
        $resultado = self::ejecutaConsulta($sql);
        $nombre = null;

        if (isset($resultado)) {
            $row = $resultado->fetch();
            $nombre = new Categorias($row);
        }

        return $nombre;
    }

    public static function insertarCategoria($nombre) {
        $sql = "INSERT INTO categoria (nombre) VALUES('$nombre');";

        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }

    public static function borrarCategoria($id) {
        $sql = "DELETE FROM categoria WHERE id='$id'";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }
    
    /* ****************************************
     * FUNCIONES SQL PARA LA CESTA DE LA COMPRA
     * ****************************************/
    //FUNCIÓN QUE INSERTA UN NUEVO PEDIDO
    public static function insertarCompra($titulo, $imagen, $cantidad, $precio, $libroID, $pedidoID) {
        $sql = "INSERT INTO compra (titulo, imagen, cantidad, precio, libroID, pedidoID)"
                . " VALUES('$titulo', '$imagen', '$cantidad', '$precio', '$libroID', '$pedidoID');";

        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }
    //Función para obtener ticket de compra (cliente)
    public static function obtieneCompra($pedidoID) {
        $sql = "SELECT * FROM compra WHERE pedidoID = '$pedidoID';";
        $resultado = self::ejecutaConsulta($sql);
        $compra = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $compra[] = new Compra($row);
                $row = $resultado->fetch();
            }
        }
        return $compra;
    }
    
    //Función para actulizar los datos del cliente en su perfil de usuario.
    public static function actualizarStock($stock, $libroId) {
        $sql = "UPDATE libro SET stock='$stock' WHERE id='$libroId';";
        $resultado = self::ejecutaConsulta($sql);
        return $resultado;
    }
}
