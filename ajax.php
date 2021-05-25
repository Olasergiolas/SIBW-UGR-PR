<?php
    include("BD.php");
    header('Content-Type: application/json');

    $busqueda = $_GET['busqueda'];

    if (strlen($busqueda) > 3){
        $BD = new BD();
        $eventos = $BD->buscarEventosPublico($busqueda);

        $datos = array_column($eventos, 'nombre_evento');

        echo(json_encode($datos));
    }

    else{
        echo(json_encode(array()));
    }
?>
