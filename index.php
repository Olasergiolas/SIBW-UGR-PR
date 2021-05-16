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

  $BD = new BD();
  $eventos = $BD->getEventosBriefing();

  echo $twig->render('index.html', ['listaeventos' => $eventos, 'usuario' => $usuario,
  'tipo_usuario' => $tipo]);
?>
