<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if (isset($_SESSION['tipo'])){
    $tipo = $_SESSION['tipo'];
  }
  else{
    $tipo = 'anonimo';
  }

  $BD = new BD();
  $eventos = $BD->getEventosBriefing();

  echo $twig->render('index.html', ['listaeventos' => $eventos, 'usuario' => $_SESSION['username'],
  'tipo_usuario' => $_SESSION['tipo']]);
?>
