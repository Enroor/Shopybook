<?php
require_once('include/DB.php');
require_once('include/utils.php');

// Comprobamos si ya se ha enviado el formulario de registro.
if (isset($_POST['register'])) {
//Mínimo 8 caracteres, almenos 1 digito, mayusucula, miniscula y simbolo.
$regexPasswd = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[a-zA-Z\d\W]{8,}/';
    //Comprobamos los datos ingresados.
    if (empty($_POST['usuario'])){
        $error = 'Debes introducir un nombre de usuario.';
    }else if(empty($_POST['password']) || empty($_POST['re-password'])){
        $error = 'Debes introducir una contraseña y repetir la contraseña.';
    }else if(!(preg_match($regexPasswd, $_POST['password']))){
        $error = 'La contraseña debe tener mínimo 8 caracteres, almenos un número, almenos una mayúscula y minúscula y un símbolo.';
    }else if($_POST['password'] != $_POST['re-password']){
        $error = 'Las contraseñas no coinciden.';
    }else{
            // Insertamos los datos del usuario a la base de datos
            if (DB::insertarUsuario(limpiaTexto($_POST['usuario']), LimpiaTexto($_POST['re-password']))) {
                session_start();
                $_SESSION['usuario'] = $_POST['usuario'];
                header("Location: index.php");
            } else {
                $error = '¡Error al registrar el usuario, el nombre de usuario ya existe!';
            }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Shopybook</title>
        <link href="./css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id='login'>
            <div id="img-login">
                <a href="index.php"><img src="imgs/logo.png" title="Shopybook" alt="Shopybook"/></a>
            </div>
            <div id="container-form">
                <form action='register.php' method='post'>
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
                    <div class='campo'>
                        <label>Repite la contraseña</label>
                        <input type='password' name='re-password' id='re-password' maxlength="50" value=""/><br/>
                    </div>
                    <div id='buttons'>
                        <a href="login.php" id="link-btn">Iniciar sesión</a>
                        <input type='submit' id='registrarse' name='register' value='Registarse' />
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
