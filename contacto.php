<?php
session_start();
require_once'templates/header.php';
require_once './include/utils.php';

$error = '';
if (isset($_POST['form-contact'])) {
    //Validamos los datos
    if (empty($_POST['nombre'])) {
        $error = 'Error: debes añadir un nombre.';
        } elseif (empty ($_POST['email']) || !(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
        $error = 'Error: e-mail incorrecto o vacío.';
        }elseif (empty($_POST['asunto'])) {
        $error = 'Error: debes escribir el asunto del mensaje.';
    } elseif (empty($_POST['mensaje'])) {
        $error = 'Error: debes escribir un mensaje.';
    } else {
        $nombre = limpiaTexto($_POST['nombre']);
        $email = limpiaTexto($_POST['email']);
        $asunto = limpiaTexto($_POST['asunto']);
        $mensaje = limpiaTexto($_POST['mensaje']);
        /****************EMAIL PARA RECIBIR LOS MENSAJES*****************/
        $para = 'shopybook@gmail.com';
        $titulo = 'Shopybook';

        $msjCorreo = "Nombre: $nombre\n E-Mail: $email\n Mensaje:\n $mensaje";

        if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['asunto']) && isset($_POST['mensaje'])) {
            if (mail($para, $titulo, $msjCorreo)) {
                $error = 'El mensaje se ha enviado.';
            } else {
                $error = 'Falló el envio: fallo en el servidor.';
            }
        }
    }
}
?>
<!-- formulario de contacto -->  
<div id="contact_form">
    <div id="formulario">      
        <h1>Contacta con nosotros</h1>
        <form action="contacto.php" method="post">     
             <p class="aviso">
                 <label class="obligatorio"><b><?php echo $error;?></b></label>
             </p>  
            <p>
                <label for="nombre" class="nombre">Nombre
                    <span class="obligatorio">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" required="obligatorio" placeholder="Escribe tu nombre">
            </p>
            <p>
                <label for="email" class="email">Email
                    <span class="obligatorio">*</span>
                </label>
                <input type="email" name="email" id="email" required="obligatorio" placeholder="Escribe tu Email">
            </p>
            <p>
                <label for="telefono" class="telefono">Teléfono
                </label>
                <input type="tel" name="introducir_telefono" id="telefono" placeholder="Escribe tu teléfono">
            </p>     
            <p>
                <label for="asunto" class="asunto">Asunto
                    <span class="obligatorio">*</span>
                </label>
                <input type="text" name="asunto" id="assunto" required="obligatorio" placeholder="Escribe un asunto">
            </p>    
            <p>
                <label for="mensaje" class="mensaje">Mensaje
                    <span class="obligatorio">*</span>
                </label>                     
                <textarea name="mensaje" class="mensaje" id="mensaje" required="obligatorio" placeholder="Escribe tu mensaje..."></textarea> 
            </p>          
            <p class="aviso">
                <span class="obligatorio"> * </span>los campos son obligatorios.
            </p>    
            <button type="submit" name="form-contact" id="enviar"><p>Enviar</p></button>        
        </form>
    </div>  
</div>
<?php require_once 'templates/footer.php'; ?>
