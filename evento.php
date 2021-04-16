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
  
  if (is_numeric($idEv) == true){
    $BD = new BD();
    $evento = $BD->getEvento($idEv);
    $comentarios = $BD->getComentarios($evento['nombre_evento'],
      $evento['fecha_evento']);
    $palabras_censuradas = $BD->getPalabrasCensuradas();
  }

  //Rechazamos la petición si ev no es un número
  else{
    http_response_code(400);
    die('Petición mal formada');
  }

  echo $twig->render('evento.html', ['evento' => $evento, 'comentarios' => $comentarios, 
    'palabras_censuradas' => $palabras_censuradas]);
?>
