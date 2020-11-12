<?php
require_once "funciones.php";
    session_start();

    if(empty($_SESSION['idioma'])){
         $_SESSION['idioma']='es';
    }

    if(isset($_POST['idProceso'])){
        $_SESSION["idTipoProceso"]=$_POST['idProceso'];
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title id='titulo1'></title>

    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="#" />
    <!-- mi estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/font-awesome.min.css" >

</head>

<body id="fondo" onload="cargarXmlLogin('<?php echo $_SESSION['idioma'] ?>')">
    <div class="container" id="marcoL">
        <div class="card card-login">
            <div class="card-header text-center" style="font-size: 1.3rem" id="titulo"></div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <strong> <label id="lenguage"></label> </strong>
                        <select name="idioma" id="idioma" onchange="cargarXmlLogin(this);">
                            <option value="es" id='espanol' selected></option>
                            <option value="en" id='ingles'></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <strong> <label id="user"></label> </strong>
                        <input class="form-control" type="text" aria-describedby="usuario" name="usuario" placeholder="Usuario">
                    </div>

                    <div class="form-group">
                        <strong> <label id="pass"></label> </strong>
                        <input class="form-control" type="password" name="passwd" placeholder="Contraseña">
                    </div>
                    
                    <div class="row justify-content-around">
                         <input class="btnAcceder" type="submit" value="Acceder" name="login" id="login">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/miscript.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
