<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if ($_SESSION['tipo'] != 'gestor' and $_SESSION['tipo'] != 'superusuario'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }
  
  $BD = new BD();
  $contenido = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $contenido = $_POST['fcuerpo'];
  }

  $listadoEventos = $BD->buscarEventos($contenido);

  echo $twig->render('listadoEventos.html', ['usuario' => $_SESSION['username'], 'tipo_usuario' => $_SESSION['tipo'],
  'eventos' => $listadoEventos]);
?>