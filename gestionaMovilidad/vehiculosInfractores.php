<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehiculos infractores</title>
    <link rel='stylesheet' href='css/style.css' type='text/css'/>
</head>
<body>
    <div>
    <?php
        function comprobarMatricula($matricula, $puntero, $fecha){
            rewind($puntero);
            while(!feof($puntero)){
                $lineaArchivos = fgets($puntero);
                $lista = explode(" ",$lineaArchivos);
                if($matricula === $lista[0]) {                    
                    if(count($lista) == 4){
                        $x = str_replace("/","-",$fecha);
                        $fechaArchivo = new DateTime($x);
                        $fechaInicio = new DateTime($lista[2]);
                        $fechaFin = new DateTime($lista[3]);
                        if($fechaArchivo >= $fechaInicio and $fechaArchivo <= $fechaFin){
                            return true;
                        }
                        return false;
                    } 
                    return true;
                }
            }
            return false; 
        }
        $listaArchivos = array('vehiculosEMT', 'taxis','servicios','residentesYHoteles','logistica');
        $punteroArchivos = array();
        foreach($listaArchivos as $valor){
            if(!file_exists("archivos/$valor.txt")) die("Error al abrir fichero.");
            $puntero = fopen("archivos/$valor.txt",'r');
            if(!$puntero) die("Error al abrir fichero.");
            array_push($punteroArchivos, $puntero);
        }
        $rutaVehiculos = 'archivos/vehiculos.txt';
        if(file_exists($rutaVehiculos)){
            echo "<h1>Vehiculos infractores:</h1>";
            $archivo = fopen($rutaVehiculos,'r');
            if(!$archivo) die("<h1>Error al abrir fichero.</h1>");
            while(!feof($archivo)){
                $matriculaInList = false;
                $linea = fgets($archivo);
                $listaPalabras = explode(" ",$linea);
                $matricula = $listaPalabras[0];
                $isElectric = $listaPalabras[count($listaPalabras)-1];
                $fecha = $listaPalabras[count($listaPalabras)-3];
                if(strcmp('electrico', $isElectric)){              
                    foreach($punteroArchivos as $valor){
                        if(comprobarMatricula($matricula, $valor, $fecha)) {
                            $matriculaInList = true;
                            break;
                        } 
                    }
                    if(!$matriculaInList) echo "<h4>$matricula</h4>";
                } 
            }
            fclose($archivo);
            foreach($punteroArchivos as $valor){
                fclose($valor);
            }
        }
        else{
            echo "<h1>Error al buscar vehiculos</h1>";
        }
        echo "<a href='movilidad.html'>Volver</a>";
    ?>
    </div>
</body>
</html>