<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $status = 1;
  if ($_SESSION['tipo'] != 'gestor'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }

  $BD = new BD();
  if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
    $infoEvento = $BD->getEvento($idEv);
  }

  echo $twig->render('editarEvento.html', ['usuario' => $_SESSION['username'], 'evento' => $infoEvento ,'status' => $status]);
?>
