<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $BD = new BD();
  $eventos = $BD->getEventosBriefing();

  echo $twig->render('index.html', ['listaeventos' => $eventos]);
?>
