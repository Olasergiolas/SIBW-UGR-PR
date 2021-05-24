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
  if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
    $BD->borrarEvento($idEv);
    header("Location: index.php");
    exit();
  }

  echo $twig->render('index.html', ['usuario' => $_SESSION['username']]);
?>
