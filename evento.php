<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $respuesta = procesarPeticion();
  if (!is_null($respuesta)){
    $evento = $respuesta['evento'];
    $comentarios = $respuesta['comentarios'];
    $palabras_censuradas = $respuesta['palabras_censuradas'];
  }

  echo $twig->render('evento.html', ['evento' => $evento, 'comentarios' => $comentarios, 
    'palabras_censuradas' => $palabras_censuradas]);
?>
