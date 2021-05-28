<?php
    require_once "./vendor/autoload.php";
    include("BD.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    session_start();
    if (isset($_SESSION['tipo'])){
      $tipo = $_SESSION['tipo'];
      $usuario = $_SESSION['username'];
    }
    else{
      $tipo = 'anonimo';
      $usuario = '';
    }

    $busqueda = $_POST['busqueda'];

    if (strlen($busqueda) > 3){
        $BD = new BD();
        $eventos = $BD->buscarEventosPublico($busqueda);

        $datos = $eventos;

        echo $twig->render('resultadosBusqueda.html', ['resultados' => $datos, 'tipo_usuario' => $tipo]);
        //echo(json_encode($datos));
    }
?>
