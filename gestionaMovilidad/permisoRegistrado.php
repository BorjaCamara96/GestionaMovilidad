<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permiso solicitado</title>
    <link rel='stylesheet' href='css/style.css' type='text/css'/>
</head>
<body>
    <div>
    <?php
        echo "<h1>Matricula: ".$_GET['matricula']." con permiso solicitado en ".$_GET['permiso']."</h1><br>";
        echo "<a href='movilidad.html'>Volver a inicio</a>";
    ?>
    </div>
</body>
</html>