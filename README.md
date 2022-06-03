# Shopybook
Proyecto tienda online DAW 

# 1.	Sistema de ayuda para su uso.

Para poder acceder al panel del administrador de Shopybook el usuario se llamará admin y su contraseña inicial será admin, pudiéndose modificar ésta ultima una vez se haya logueado y entrado en su perfil desde la aplicación web para que sea más segura.

En el panel administrador podrás añadir nuevos libros, editar los libros existentes, eliminar los libros que no necesite, añadir categorías o eliminarlas y ver los pedidos de los clientes y modificar el estado del pedido.

En el icono del usuario podrás acceder a tu perfil dónde solamente podrás modificar todo menos tu nombre de usuario, recuerda que el usuario siempre se llamará admin.
Podrás buscar un libro en concreto desde el buscador siempre y cuando nos encontremos en la página Inicio.

Si el cliente se quiere poner en contacto con el administrador podrá mandar un mensaje a través del formulario de contacto de Shopybook siempre que el servidor donde esté alojado lo permita. Para recibir los emails de los clientes tendrás que modificar el archivo contacto.php y poner tu e-mail en la variable $para.
El cliente registrado podrá cambiar sus datos personales desde su perfil, así como ver sus pedidos y el estado en el que se encuentran.


# 2.	Manual de instalación.
#  2.1.	Instalación en local.

Para instalar Shopybook de manera local necesitaremos un servidor web como Xampp donde podremos crear nuestro servidor de manera local utilizando Apache y PhpMyAdmin para la gestión de la base de datos.

Para crear la base de datos, accedemos al panel de PhpMyAdmin mediante localhost a través de la url de nuestro navegador. Para crear la base de datos copiamos el contenido del archivo BD_shopybook.sql del directorio sql en insertar sentencia SQL.

A continuación, copiamos el contenido del archivo datos_shopybook .sql e insertará datos de pruebas, así como el usuario ‘admin’ para la administración de la aplicación web.
La configuración de Shopybook está programada para que funcione de manera local por defecto.
 
#  2.2.	Instalación en el servidor.

Para la instalación en el servidor debemos modificar algunos archivos de configuración para que funcione la aplicación web.
En la carpeta config, accedemos al archivo settings.php y cambiar los siguientes parámetros:

•	Host= ‘host del servidor’
•	Dbname = ‘nombre de la base de datos’
•	DB_USER = ‘usuario de la base de datos’
•	DB_PASSWD = ‘Contraseña de la base de datos’

En mi caso, estoy utilizando un servidor gratuito en el cual me asignan un nombre para la base de datos, usuario y contraseña.
A continuación, al acceder a PhpMyAdmin desde tu servidor debemos crear las tablas con el archivo BD_shopybook_server.sql, para añadir los datos de pruebas y claves foráneas con el archivo datos_shopybook_server.sql, ambos localizados en la carpeta sql.
Con estos scripts hemos creado el usuario admin que se encargará de la administración de Shopybook.

Recuerda cambiar la variable $para en el archivo contacto.php y poner tu email para recibir mensajes de los clientes a través del formulario de contacto de la aplicación web.
Para lanzar la aplicación deberás acceder a tu servidor FTP y copiar todos lo archivos de la carpeta shopybook, una vez modificados los archivos anteriormente mencionados.
