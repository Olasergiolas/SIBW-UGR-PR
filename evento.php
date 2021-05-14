<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $BD = new BD();

  $respuesta = procesarPeticion($BD);
  if (!is_null($respuesta)){
    $evento = $respuesta['evento'];
    $comentarios = $respuesta['comentarios'];
    $palabras_censuradas = $respuesta['palabras_censuradas'];
  }

  if (isset($_SESSION['username'])){
    $datosUsuario = $BD->getDatosUsuario($_SESSION['username']);
  }

  echo $twig->render('evento.html', ['evento' => $evento, 'comentarios' => $comentarios, 
    'palabras_censuradas' => $palabras_censuradas, 'usuario' => $_SESSION['username'],
    'email' => $datosUsuario['email']]);
?>
