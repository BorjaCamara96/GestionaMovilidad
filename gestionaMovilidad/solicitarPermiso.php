<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de permiso</title>
    <link rel='stylesheet' href='css/style.css' type='text/css'/>
</head>
<body>
    <?php
        function dibujarFormMovilidad(){
            $formulario = "<table><form action='solicitarPermiso.php' method='post'><tr><td>";
            $formulario .= "<tr><td><h1>GestionaMovilidad</h1></td></tr><tr><td>";
            $formulario .= "<select name='permiso'>";
            $formulario .= "<option value=''></option>";
            $formulario .= "<option value='EMT'>EMT</option>";
            $formulario .= "<option value='Taxi'>Taxi</option>";
            $formulario .= "<option value='Servicio'>Servicio</option>";
            $formulario .= "<option value='Residentes'>Residentes</option>";
            $formulario .= "<option value='Hoteles'>Hoteles</option>";
            $formulario .= "<option value='Logistica'>Logistica</option>";
            $formulario .= "</select>";
            $formulario .= "</td></tr><tr><td>";
            $formulario .= "<input type='submit' name='enviarTipo' value='Solicitar permiso'/>";
            $formulario .= "</td></tr></form><tr><td>";
            $formulario .= "<a href='vehiculosInfractores.php'>Listado de infractores</a>";
            $formulario .= "</td></tr></table>";
            $formulario .= "<p>Debe seleccionar una opcion de permiso para solicitarlo.</p>";
            return $formulario;
        }
        
        $controlMatricula = false;
        $controlFecha = false;
        $controlText = false;
        $controlConfirma = false;
        $errores = "";

        function agregarErrores($title){
            $GLOBALS['errores'] .= "<p>Campo $title erroneo</p>";
        }
        function validaMatricula($variable){
            if(!empty($variable)){
                $patron = '/^[0-9]{4}\-[BCDFGHJKLMNPQRSTVWXYZ]{3}$/';
                if(preg_match($patron, $variable)){
                    return true;
                }
                return false;
            }
            return false;
        }
        function validaFecha($variableIni,$variableFin,$permiso){
            if($permiso == 'Hoteles'){
                if(!empty($variableIni) and !empty($variableFin)){
                    $fechaHoy = new DateTime();
                    $fechaInicial = new DateTime($variableIni);
                    $fechaFin = new DateTime($variableFin);
                    if($fechaInicial > $fechaHoy and $fechaFin > $fechaInicial){
                        return true;
                    }
                    return false;
                }
                return false;
            }
            else if($permiso == 'Residentes'){
                if(!empty($variableIni)){
                    $fechaHoy = new DateTime();
                    $fechaInicial = new DateTime($variableIni);
                    if($fechaInicial > $fechaHoy){
                        return true;
                    }
                    return false;
                }
                return false;
            }
            return true;
        }
        function validaEmpty($variable){
            if(!empty($variable)){
                return true;
            }
        }
        function validaIsset($variable){
            if(isset($variable)){
                return true;
            }
        }
        function dibujaText($title, $valid, $name){
            $formulario = "<tr><td>$title</td>";
            $formulario .= "<td><input type='text' name='$name' value='";
            if($valid) $formulario .= "$_POST[$name]";
            $formulario .= "'/>";
            $formulario .= "</td></tr>";
            if(!$valid) agregarErrores($title);
            return $formulario;
        }
        function dibujaSelect($title, $valid, $name, $list){
            $formulario = "<tr><td>$title</td>";
            $formulario .= "<td><select name='permiso'>";
            foreach($list as $valor){
                $formulario .= "<option value='$valor' ";
                if($valor == $_POST[$name]) $formulario .= "selected";
                $formulario .= ">$valor</option>";
            }
            $formulario .= "/>";
            $formulario .= "</td></tr>";
            if(!$valid) agregarErrores($title);
            return $formulario;
        }
        function dibujaFecha($title, $valid, $name){
            $formulario = "<tr><td>$title</td>";
            $formulario .= "<td><input type='date' name='$name' value='";
            if($valid) $formulario .= "$_POST[$name]";
            $formulario .= "'/>";
            $formulario .= "</td></tr>";
            if(!$valid) agregarErrores($title);
            return $formulario;
        }
        function dibujaCheck($title, $valid, $name){
            $formulario = "<tr><td>$title</td>";
            $formulario .= "<td><input type='checkbox' name='$name' ";
            if($valid) $formulario .= "checked";
            $formulario .= "/>";
            $formulario .= "</td></tr>";
            if(!$valid) agregarErrores($title);
            return $formulario;
        }
        function dibujaSubmit($value, $name){
            $formulario = "<tr><td>";
            $formulario .= "<input type='submit' name='$name' value='$value'/>";
            $formulario .= "</td><td><a href='movilidad.html'>Volver</a></td></tr>";
            return $formulario;
        }
        
        if(empty($_POST['permiso'])){
            echo dibujarFormMovilidad();
        }
        else{
            $permiso = trim($_POST['permiso'],"/");
            $formulario = "<form action='solicitarPermiso.php' method='post'><div>";
            $formulario .= "<h1>Solicitud de $permiso</h1>";
            $formulario .= "<table>";
            //Campo TIPO
            $formulario .= "<input type='hidden' name='permiso' value=".$_POST['permiso']."/>";
            //Campo MATRICULA.
            $controlMatricula = validaMatricula($_POST['matricula']);
            $formulario .= dibujaText('Matricula',$controlMatricula,'matricula');
            switch ($permiso){
                case 'EMT':
                    //Campo RUTA
                    $controlText = validaEmpty($_POST['ruta']);
                    $formulario .= dibujaText('Ruta',$controlText,'ruta');
                    $contenidoTexto = 'ruta';
                    $controlFecha = true;
                    break;
                case 'Taxi':
                    //Campo NOMBRE
                    $controlText = validaEmpty($_POST['nombre']);
                    $formulario .= dibujaText('Nombre',$controlText,'nombre');
                    $contenidoTexto = 'nombre';
                    $controlFecha = true;
                    break;
                case 'Servicio':
                    //Campo NOMBRE
                    $controlText = validaEmpty($_POST['servicio']);
                    $formulario .= dibujaText('Servicio',$controlText,'servicio');
                    $contenidoTexto = 'servicio';
                    $controlFecha = true;
                    break;
                case 'Residentes':
                    //Campo DIRECCION
                    $controlText = validaEmpty($_POST['direccion']);
                    $formulario .= dibujaText('Direccion',$controlText,'direccion');
                    $contenidoTexto = 'direccion';
                    //Campo FECHAS
                    $controlFecha = validaFecha($_POST['fechaInicial']," ",$permiso);
                    $formulario .= dibujaFecha('Fecha',$controlFecha,'fechaInicial');
                    break;
                case 'Hoteles':
                    //Campo DIRECCION
                    $controlText = validaEmpty($_POST['direccion']);
                    $formulario .= dibujaText('Direccion',$controlText,'direccion');
                    $contenidoTexto = 'direccion';
                    //Campo FECHAS
                    $controlFecha = validaFecha($_POST['fechaInicial'],$_POST['fechaFin'],$permiso);
                    $formulario .= dibujaFecha('Fecha inicial',$controlFecha,'fechaInicial');
                    $formulario .= dibujaFecha('Fecha fin',$controlFecha,'fechaFin');
                    break;
                case 'Logistica':
                    //Campo EMPRESA
                    $controlText = validaEmpty($_POST['empresa']);
                    $formulario .= dibujaText('Empresa',$controlText,'empresa');
                    $contenidoTexto = 'empresa';
                    $controlFecha = true;
                    break;
            }
            //Campo CONFIRMA 
            $controlConfirma = validaIsset($_POST['confirma']);
            $formulario .= dibujaCheck('Confirma',$controlConfirma,'confirma');  
            //Campo ENVIAR
            $formulario .= dibujaSubmit('Enviar', 'enviar');
            $formulario .= "</table></div></form>";
            if($controlMatricula and $controlFecha and $controlText and $controlConfirma){
                $formulario .= "<input type='hidden' name='validaForm' value='VALIDO'/>";
            }
            if(isset($_POST['enviar'])){
                $formulario .= $errores;
            }
            echo $formulario;
            if($controlMatricula and $controlFecha and $controlText and $controlConfirma){
                $matricula = $_POST['matricula'];
                $fechaInicio = $_POST['fechaInicial'];
                $fechaFin = $_POST['fechaFin'];
                $text = $_POST[$contenidoTexto];
                header("Location:permisoRegistrado.php?matricula=$matricula&permiso=$permiso");
                switch ($permiso){
                    case 'EMT':
                        $putDates = fopen('archivos/vehiculosEMT.txt','a');
                        fwrite($putDates,"\n$matricula $text");
                        fclose($putDates);
                        break;
                    case 'Taxi':
                        $putDates = fopen('archivos/taxis.txt','a');
                        fwrite($putDates,"\n$matricula $text");
                        fclose($putDates);
                        break;
                    case 'Servicio':
                        $putDates = fopen('archivos/servicios.txt','a');
                        fwrite($putDates,"\n$matricula $text");
                        fclose($putDates);
                        break;
                    case 'Residentes':
                        $putDates = fopen('archivos/residentesYHoteles.txt','a');
                        $fechaFin = new DateTime($fechaInicio);
                        $fechaFin->modify('1 year'); 
                        $fechaFinFormat = $fechaFin->format("Y-m-d");
                        fwrite($putDates,"\n$matricula $text $fechaInicio $fechaFinFormat");
                        fclose($putDates);
                        break;
                    case 'Hoteles':
                        $putDates = fopen('archivos/residentesYHoteles.txt','a');
                        fwrite($putDates,"\n$matricula $text $fechaInicio $fechaFin");
                        fclose($putDates);
                        break;
                    case 'Logistica':
                        $putDates = fopen('archivos/logistica.txt','a');
                        fwrite($putDates,"\n$matricula $text");
                        fclose($putDates);
                        break;
                }
            }
        }
    ?>
</body>
</html>