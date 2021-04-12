<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $mysqli = conectarBD();

  $eventos = getEventosBriefing($mysqli);

  echo $twig->render('index.html', ['listaeventos' => $eventos]);
?>
