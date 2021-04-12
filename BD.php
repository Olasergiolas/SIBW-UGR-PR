<?php
  function getEvento($idEv, $mysqli) {
    //Parámetros del evento
    $nombreEvento = "Nombre por defecto";
    $fechaEvento = "01/01/1970";
    $organizador = "default";
    $descripcion = "default";
    $url = "default";

    //Parámetros de la imagen del evento
    $nombre_imagen = "default_event.jpg";
    $copyright_imagen = "default";

    //Petición de la información del evento
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
    $descripcion_procesada = procesarCuerpoEvento($descripcion);


    //Petición de la información del evento
    $res = $mysqli->query("SELECT nombre_imagen, copyright FROM imagenes
    WHERE nombre_evento = '$nombreEvento' AND fecha = '$fechaEvento'");
  
    if ($res->num_rows > 0){
      //Datos de la imágen del evento
      $row = $res->fetch_assoc();
      $nombre_imagen = $row['nombre_imagen'];
      $copyright_imagen = $row['copyright'];
    }

    $evento = array('nombre_evento' => $nombreEvento,
    'fecha_evento' => $fechaEvento, 'organizador' => $organizador, 'descripcion' => $descripcion_procesada,
    'url' => $url, 'nombre_imagen' => $nombre_imagen, 'copyright' => $copyright_imagen);

    return $evento;
  }

  function procesarCuerpoEvento($descripcion) {
    $descripcion_procesada = '<p class="event_body">';
    $descripcion_procesada .= str_replace("\n", '</p><p class="event_body">', $descripcion);
    $descripcion_procesada .= '</p>';

    return $descripcion_procesada;
  }
  
  function conectarBD() {
    $mysqli = new mysqli("localhost", "sergiogarcia", "QVeApauxsYj3XcktpdnO", "SIBW");
    if ($mysqli->connect_errno) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    return $mysqli;
  }

  function getComentarios($nombre_evento, $fecha_evento, $mysqli){
    $res = $mysqli->query("SELECT usuario, fecha_hora, contenido FROM comentarios
    WHERE nombre_evento = '$nombre_evento' AND fecha_evento = '$fecha_evento'");
  
    /*if ($res->num_rows > 0){
      //Datos de la imágen del evento
      $row = $res->fetch_assoc();
      $nombre_imagen = $row['nombre_imagen'];
      $copyright_imagen = $row['copyright'];
    }*/

    $comentarios = array();
    if ($res->num_rows > 0){
      while($row = $res->fetch_assoc()){
        $comentario = array('usuario' => $row['usuario'], 'fecha_hora' => $row['fecha_hora'],
        'contenido' => $row['contenido']);

        array_push($comentarios, $comentario);
      }
    }

    return $comentarios;
  }

  function getEventosBriefing($mysqli){
    $eventos = array();

    $res = $mysqli->query("SELECT nombre_evento, icono FROM eventos");
    if ($res->num_rows > 0){
      while($row = $res->fetch_assoc()){
        $evento = array('nombre_evento' => $row['nombre_evento'], 'icono' => $row['icono']);

        array_push($eventos, $evento);
      }
    }

    return $eventos;
  }
?>