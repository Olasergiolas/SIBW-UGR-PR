<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
  } else {
    $idEv = -1;
  }

  //QUITAR ESTO, EL CONTROLADOR DEBERÍA DE COMPROBAR SI SE HA CONECTADO YA( != NULL), Y SI NO, HACERLO
  //Hacer una clase para la BD
  $mysqli = conectarBD();
  $evento = getEvento($idEv, $mysqli);
  $comentarios = getComentarios($evento['nombre_evento'],
    $evento['fecha_evento'], $mysqli);
  $palabras_censuradas = getPalabrasCensuradas($mysqli);

  echo $twig->render('evento.html', [evento => $evento, comentarios => $comentarios, 
    palabras_censuradas => $palabras_censuradas]);
?>
