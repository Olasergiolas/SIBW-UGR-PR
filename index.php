<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $mysqli = conectarBD();

  $eventos = [
    'info' => [
      'info_fila1' => ['Cyber Apocalypse 2021', 'PlaidCTF 2021', 'LINE CTF 2021'],
      'info_fila2' => ['DEFCON', 'RSA Conference', 'Hack in Paris'],
      'info_fila3' => ['Black Hat USA', 'Google CTF', 'NULLCON'],
    ],
  ];

  //$eventos = getEventosBriefing($mysqli);

  echo $twig->render('index.html', ['listaeventos' => $eventos]);
?>
