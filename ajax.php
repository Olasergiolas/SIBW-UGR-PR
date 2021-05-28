<?php
    require_once "./vendor/autoload.php";
    include("BD.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $busqueda = $_POST['busqueda'];

    if (strlen($busqueda) > 3){
        $BD = new BD();
        $eventos = $BD->buscarEventosPublico($busqueda);

        $datos = $eventos;

        echo $twig->render('resultadosBusqueda.html', ['resultados' => $datos]);
        //echo(json_encode($datos));
    }
?>
