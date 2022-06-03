<?php
require_once('include/DB.php');
require_once('include/utils.php');

// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {

    if (empty($_POST['usuario']) || empty($_POST['password']))
        $error = 'Debes introducir un nombre de usuario y una contraseña';
    else {
        // Comprobamos las credenciales con la base de datos
        if (DB::verificaUsuario(limpiaTexto($_POST['usuario']), limpiaTexto($_POST['password']))) {
            session_start();
            $_SESSION['usuario'] = $_POST['usuario'];
            header("Location: index.php");
        } else {
            // Si las credenciales no son válidas, se vuelven a pedir
            $error = '¡Usuario o contraseña incorrecto!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Shopybook</title>
        <link rel="icon" type="image/png" href="./imgs/favicon.png" />
        <link href="./css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id='login'>
            <div id="img-login">
                <a href="index.php"><img src="imgs/logo.png" title="Shopybook" alt="Shopybook"/></a>
            </div>
            <div id="container-form">
                <form action='login.php' method='post'>
                    <div id="container-login">
                        <span id="text-login">LOGIN</span>
                    </div>
                    <div>
                        <span class='error'><?php echo (isset($error) ? $error : ""); ?></span>
                    </div>
                    <div class='campo'>
                        <label>Usuario</label>
                        <input type='text' name='usuario' id='usuario' maxlength="50" value=""/><br/>
                    </div>
                    <div class='campo'>
                        <label>Contraseña</label>
                        <input type='password' name='password' id='password' maxlength="50" value=""/><br/>
                    </div>
                    <div id='buttons'>
                        <input type='submit' id='iniciar_sesion' name='enviar' value='Iniciar sesión' />
                        <a href="register.php" id="link-btn">Registrarse</a>
                    </div>
                </form>
            </div>
        </div>
        <footer>
            <div>
                Shopybook 2022 ©
            </div>
        </footer> 
    </body>
</html>