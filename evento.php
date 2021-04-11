<?php
  require_once "./vendor/autoload.php";

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  

  $nombreEvento = "Nombre por defecto";
  $fechaEvento = "01/01/1970";
  $organizador = "default";
  $descripcion = "default";
  $url = "default";


  $nombre_imagen = "default_event.jpg";
  $copyright_imagen = "default";



  if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
  } else {
    $idEv = -1;
  }


  $mysqli = new mysqli("localhost", "sergiogarcia", "QVeApauxsYj3XcktpdnO", "SIBW");
  if ($mysqli->connect_errno) {
    echo ("Fallo al conectar: " . $mysqli->connect_error);
  }
  $res = $mysqli->query("SELECT nombre_evento, fecha, organizador, descripcion, url
    FROM eventos WHERE id =" . $idEv);
  
  if ($res->num_rows > 0){
    //Datos del evento
    $row = $res->fetch_assoc();
    $nombreEvento = $row['nombre_evento'];
    $fechaEvento = $row['fecha'];
    $organizador = $row['organizador'];
    $descripcion = $row['descripcion'];
    $url = $row['url'];
  }

  $descripcion_procesada = '<p class="event_body">';
  $descripcion_procesada .= str_replace("\n", '</p><p class="event_body">', $descripcion);
  $descripcion_procesada .= '</p>';



  $res = $mysqli->query("SELECT nombre_imagen, copyright FROM imagenes
  WHERE nombre_evento = '$nombreEvento' AND fecha = '$fechaEvento'");

  if ($res->num_rows > 0){
    //Datos de la imágen del evento
    $row = $res->fetch_assoc();
    $nombre_imagen = $row['nombre_imagen'];
    $copyright_imagen = $row['copyright'];
  }



  echo $twig->render('evento.html', ['nombre_evento' => $nombreEvento,
   'fecha_evento' => $fechaEvento, 'organizador' => $organizador, 'descripcion' => $descripcion_procesada,
   'url' => $url, 'nombre_imagen' => $nombre_imagen, 'copyright' => $copyright_imagen]);
?>
