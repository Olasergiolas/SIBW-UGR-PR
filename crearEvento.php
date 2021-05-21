<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if ($_SESSION['tipo'] != 'gestor'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }

  $BD = new BD();
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fecha = $_POST['ffecha'];
    $titulo = $_POST['ftitle'];
    $organizador = $_POST['forganizador'];
    $cuerpo = $_POST['cuerpo'];
    $url = $_POST['fwebsite'];
    $miniatura = $_POST['fminiatura'];
    $imagenes = $_POST['fimagenes'];

    $datosEvento = array('fecha' => $fecha, 'nombre' => $titulo, 'organizador' => $organizador,
      'descripcion' => $cuerpo, 'url' => $url);

    $BD->addEvento($datosEvento);
  }

  echo $twig->render('crearEvento.html', ['usuario' => $_SESSION['username']]);
?>
