<?php
require_once "funciones.php";

session_start();

//cargamos los xml correspondientes
if($_SESSION["idioma"] == 'es'){
    $documento=simplexml_load_file("resources/es.xml");
}
else{
    $documento=simplexml_load_file("resources/en.xml");
}

if(isset( $_SESSION['usuario']) ){
    $usu = $_SESSION['usuario'];
    $nombreUsu = $_SESSION['nombre'];
    $tipo = $_SESSION["rolUsu"];
}

$conn = conexion();
// RECOGEMOS LOS POSIBLES ESTADOS DE UNA EVALUACION

    $campoNombreEmpresa = "";
    $campoFechaDesde =  "";
    $campoFechaHasta =  "";
    $campoEstado = "";

    if ( isset( $_POST['buscar'] ) ) {

        $campoNombreEmpresa = $_POST['inputEmpresa'];
        $campoFechaDesde = formatear_fecha(strtotime( $_POST['inputDesde'] ));        
        $campoFechaHasta = formatear_fecha(strtotime( $_POST['inputHasta'] ));
        
        if ($campoFechaDesde == "1970-01-01" ) {
             $campoFechaDesde =  "";
        }
        if($campoFechaHasta == "1970-01-01" ) {
            $campoFechaHasta =  "";
        }
        
    }
 ?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $documento->ConsultaEval->Titulo ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- mi estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="#" />
</head>

<body>
    <?php include 'menu.php'?>
    <div class="container"> <br>
        <h1><?php echo $documento->ConsultaEval->Titulo2 ?></h1> <br>
        <form method="post">
            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <strong> <label for="inputEmpresa"><?php echo $documento->ConsultaEval->Empresa ?></label> </strong>
                    <input type="text" class="form-control" name="inputEmpresa" id="inputEmpresa" maxlength="30" placeholder="Nombre Empresa" value="<?php echo $campoNombreEmpresa; ?>">
                </div>

                <div class="form-group col-md-4">
                    <!-- Example split danger button -->
                    <div style="float:left">
                        <strong> <label for="inputEstado"><?php echo $documento->ConsultaEval->Estado ?></label> </strong>
                        <select name="inputEstado" id="inputEstado">
                            <?php cargaEstados( $conn ); 
                              ?>
                        </select>
                    </div>
                        <div style="float:left;margin-left:10%">
                        <strong> <label for="inputTipoProceso"><?php echo $documento->ConsultaEval->Proceso ?></label> </strong>
                        <select name="inputProceso" id="inputProceso">
                            <option value='0' selected><?php echo $documento->ConsultaEval->Todas ?></option>
                            <?php cargaProcesos( $conn ); 
                              ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- fila 2 -->
            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <strong> <label for="inputDesde"><?php echo $documento->ConsultaEval->FechaDesde ?></label>  </strong>
                    <input type="date" class="form-control" id="inputDesde" name="inputDesde" value="<?php echo $campoFechaDesde; ?>">
                </div>

                <div class="form-group col-md-4">
                    <strong> <label for="inputHasta"><?php echo $documento->ConsultaEval->FechaHasta ?></label> </strong>
                    <input type="date"  class="form-control" id="inputHasta" name="inputHasta" value="<?php echo $campoFechaHasta; ?>">
                </div>
            </div>

            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <input class="btn btnBuscar" id="submit" type="submit" name="buscar" value="<?php echo $documento->ConsultaEval->Buscar ?>">
                </div>
            </div>
        </form>
    </div>
    
    <?php
        if ( isset( $_POST['buscar'] ) ) {
            $empresa = $_POST['inputEmpresa'];
            $estado = $_POST['inputEstado'];
            $fDesde = formatear_fecha( strtotime( $_POST['inputDesde'] ));
            $fHasta = formatear_fecha(strtotime( $_POST['inputHasta'] ));
            $proceso = $_POST['inputProceso'];

            $sqlquery = "SELECT evaluaciones.idEvaluacion, evaluaciones.idTipoProceso, evaluaciones.codEvaluacion, evaluaciones.nombreEmpresa, evaluaciones.fechaDesde, evaluaciones.fechaHasta, estados.nombreEstado, evaluaciones.idEstadoEval
            FROM evaluaciones 
            INNER JOIN estados on estados.idEstado = evaluaciones.idEstadoEval
            WHERE 1=1" ;            
                
            $empresa = trim( $empresa );
                
            if ( $empresa != "" ) {
                $sqlquery .=  " AND evaluaciones.nombreEmpresa LIKE '%$empresa%'";
            }

            if ( $fDesde != "1970-01-01" ) {

                if ( $fHasta != "1970-01-01" ) {
                    $sqlquery .= " AND evaluaciones.fechaDesde >= '$fDesde' AND evaluaciones.fechaHasta <= '$fHasta'";

                } else {
                    $sqlquery .= " AND evaluaciones.fechaDesde >= '$fDesde'";
                }
            } //cierre $fDesde

            //Cambio Asier fecha_hasta
            else if($fHasta != "1970-01-01" &&  $fDesde == "1970-01-01"){
                $sqlquery .= " AND evaluaciones.fechaHasta <= '$fHasta'";
            }
            
            if ($estado != 0) {
                $sqlquery.= " AND evaluaciones.idEstadoEval = $estado";
            } 

            if($proceso != 0){
              $sqlquery.= " AND evaluaciones.idTipoProceso = $proceso";  
            }
            $sqlquery .= " ORDER BY evaluaciones.idEvaluacion ASC";
            //print ($sqlquery);
                
            $sql = mysqli_query( $conn, $sqlquery );

            if ( mysqli_affected_rows( $conn ) != 0 ) {
                echo '<form action="evaluacion.php" method="POST" id="formResultado">                    
                        <div class = "container">
                            <table class = "table table-hover">
                                <thead>
                                    <tr>                                                   
                                        <th scope="col"> Evaluación </th>
                                        <th scope="col"> Empresa </th>
                                        <th scope="col"> Fecha desde </th>
                                        <th scope="col"> Fecha hasta </th>
                                        <th scope="col"> Estado </th>
                                        </tr>
                                </thead>';
                                echo '<tbody>';
                    $cont = 1;
                    while( $data = mysqli_fetch_array( $sql ) ) {
                                            
                        
                            echo '<tr onclick="fConsultarEvaluacion('; echo $data[0]; echo')" style="cursor:pointer;">';
                                echo '<td> <span>'; echo $data[2]; echo '</span> </td>';
                                echo '<td> <span>'; echo $data[3]; echo '</span> </td>';
                                echo '<td> <span>'; if($data[4]!=""){echo date( 'd-m-Y', strtotime($data[4]));} echo '</span> </td>';
                                echo '<td> <span>'; if($data[5]!=""){echo date( 'd-m-Y', strtotime($data[5]));} ; echo '</span> </td>';
                                echo ' <td title="'; echo  $data[6]; echo '" >';
                    
                                if ( $data[7] == 1 ) {
                                    echo '<img src = "img/red.png" alt = "'; echo  $data[6]; echo '" name="no iniciada">';
                                } else if ( $data[7] == 2 ) {
                                    echo '<img src = "img/yellow.png" alt = "'; echo  $data[6]; echo '" name="iniciada">';
                                } else if ( $data[7] == 3 ) {
                                    echo '<img src = "img/green.png" alt = "'; echo  $data[6]; echo '" name="Finalizada">';
                               } 
                                
                            echo ' </td></tr>';
                       
                        $cont ++;
                    } //cierre while
                 echo '</tbody>';
            echo '</table>
            </div> 
            <input type="hidden" id="idEvalSeleccionada" name="idEvalSeleccionada" value=""/>
            <input type="hidden" id="consulta" name="consulta" value="consulta"/>
            </form>';

                
        // hay que poner el atributo de selected en el option
        $campoEstado = $_POST['inputEstado'];
        echo '<script type="text/javascript">document.getElementById("inputEstado").selectedIndex  = ' . $campoEstado . ' ;</script>';
                
    } else {
        echo '<h1 class="text-center"> No hay resultados </h1>';
        
    }//cierre if

}  ?>
    
    <script>
        
            function fConsultarEvaluacion(IdEval){
                //Llamar a la ventana de consulta de evaluaciones
                document.all.idEvalSeleccionada.value = IdEval
                document.getElementById("formResultado").submit();
                //window.location.href = "usuarioEval.php?idEval=" + IdEval                
            }
        
    </script>    

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/miscript.js"></script>
</body>

</html>
