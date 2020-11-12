 <?php 
//ALUMNOS
require_once "funciones.php";
$conn = conexion();

    //cargamos los xml correspondientes
if($_SESSION["idioma"] == 'es'){
    $documento=simplexml_load_file("resources/es.xml");
}
else{
    $documento=simplexml_load_file("resources/en.xml");
}

if(isset($_POST['idiomaIcon'])){
    session_start();
    if($_POST['idiomaIcon']== 'es'){
        $documento=simplexml_load_file("resources/es.xml");
    }
    else{
        $documento=simplexml_load_file("resources/en.xml");
    }
    $_SESSION['idioma']=$_POST['idiomaIcon'];
}


if(isset($_POST['idProceso'])){
        $_SESSION["idTipoProceso"]=$_POST['idProceso'];
    }

if(isset($_SESSION['usuario'])){
    $usu = $_SESSION['usuario'];
    $nombreUsu = $_SESSION['nombre'];
    $tipo = $_SESSION["rolUsu"];
} 
$conn=conexion();

    // depende del tipo de usuario cargaremos un menu u otro
    if ( $tipo == 1 ) {
?>
 
        <nav class="navbar-expand-lg navbar-light bg-light" >
            <a style="margin-left: 1%" class="navbar-brand" href="sarrera.php"> <?php echo $nombreUsu; ?> </a>
            <div class="div_iconos">
                <button title="<?php echo $documento->Menu->TooltipEs ?>" class="boton_paises" onclick="cambiarIdioma('es')"> <?php echo $documento->Menu->Es ?></button>
                <button title="<?php echo $documento->Menu->TooltipEn ?>" class="boton_paises" onclick="cambiarIdioma('en')"><?php echo $documento->Menu->En ?></button>
            </div>
            <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <button type="button" class="btn btn-light" onclick="goConsultaPreg();"> <?php echo $documento->Menu->Mantenimiento ?> </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-light" onclick="goConsultaEval();"> <?php echo $documento->Menu->Consulta ?> </button>
                    </li>
                    <li class="nav-item active">
                        <button type="button" class="btn btn-light" onclick="goNuevaEval('menu',1);"> <?php echo $documento->Menu->Nueva1 ?> </button>
                    </li>
                    <li class="nav-item active">
                        <button type="button" class="btn btn-light" onclick="goNuevaEval('menu',2);"> <?php echo $documento->Menu->Nueva2 ?> </button>
                    </li>
                </ul>
                <span class="navbar-text">
                    <button type="button" class="btn btn-light" onclick="cerrarSesion();"> <?php echo $documento->Menu->Cerrar ?></button>
                </span>
            </div>
           
        </nav>
        
<?php } else { ?>
        
        <nav class="navbar navbar-light bg-light">
            <span class="navbar-brand" href="#"> <b>
            <!-- Si es tu evaluacion, se muestra tu nombre  -->
            <?php 

                 $eval = $_SESSION["idEvaluacion"];

                $usuAutoevaluado = mysqli_query($conn, "SELECT nombreUsu FROM usuarios INNER JOIN evaluaciones ON evaluaciones.idEvaluacion = usuarios.idEvaluacion
                WHERE evaluaciones.idEvaluacion = $eval AND usuarios.rolUsu = 2");
                $data = mysqli_fetch_array( $usuAutoevaluado );
                $nombreAutoeval = $data[0];
                $_SESSION["nombreAutoeval"] = $nombreAutoeval;


                if($tipo == 2){
                    echo $nombreUsu; 
                    } else {
                        echo $nombreUsu . "</b> realizando evaluación para: <b>" . $nombreAutoeval;
                    }
            ?>
            </b></span> 
            <button class="btn btn-outline-success my-2 my-sm-0" onclick="cerrarSesion();"><?php echo $documento->Menu->Cerrar ?></button>

        </nav>
  <?php  } ?>    
        
