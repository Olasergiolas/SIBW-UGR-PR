<?php
  class BD {
    private $mysqli = NULL;

    public function __construct() {
      $this->mysqli = $this->conectarBD();
    }

    function getEvento($idEv) {
      //Parámetros del evento
      $nombreEvento = "Nombre por defecto";
      $fechaEvento = "1970/01/01";
      $organizador = "default";
      $descripcion = "default";
      $url = "default";
  
      //Parámetros de la imagen del evento
      $nombre_imagen = "default_event.jpg";
      $copyright_imagen = "default";

      //Petición de la información del evento
      $q = "SELECT nombre_evento, fecha, organizador, descripcion, url
        FROM eventos WHERE id = ?";
      $q_preparada = $this->mysqli->prepare($q);
      $q_preparada->execute([$idEv]);
      $res = $q_preparada->fetch();
  
      //Datos del evento
      if (!empty($res)){
        $nombreEvento = $res['nombre_evento'];
        $fechaEvento = $res['fecha'];
        $organizador = $res['organizador'];
        $descripcion = $res['descripcion'];
        $url = $res['url'];
      }
      
      $descripcion_procesada = $this->procesarCuerpoEvento($descripcion);

      $q = "SELECT nombre_imagen, copyright FROM imagenes
      WHERE nombre_evento = ? AND fecha = ?";
      $q_preparada = $this->mysqli->prepare($q);
      $q_preparada->execute([$nombreEvento, $fechaEvento]);
      $res = $q_preparada->fetch();

      if (!empty($res)){
        $nombre_imagen = $res['nombre_imagen'];
        $copyright_imagen = $res['copyright'];
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
      $pdo = NULL;
      try {
        $pdo = new PDO("mysql:host=localhost;dbname=SIBW", 'sergiogarcia', 'QVeApauxsYj3XcktpdnO');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo "Conexión fallida: " . $e->getMessage();
      }
      return $pdo;
    }
  
    function getComentarios($nombre_evento, $fecha_evento){
      $q = "SELECT usuario, fecha_hora, contenido FROM comentarios
        WHERE nombre_evento = ? AND fecha_evento = ?";
      $q_preparada = $this->mysqli->prepare($q);
      $q_preparada->execute([$nombre_evento, $fecha_evento]);

      $comentarios = array();
      while($res = $q_preparada->fetch()){
        $comentario = array('usuario' => $res['usuario'], 'fecha_hora' => $res['fecha_hora'],
        'contenido' => $res['contenido']);

        array_push($comentarios, $comentario);
      }
  
      return $comentarios;
    }
  
    function getEventosBriefing(){
      $eventos = array();

      $q = "SELECT nombre_evento, icono, id FROM eventos";
      $q_preparada = $this->mysqli->prepare($q);
      $q_preparada->execute();

      while($res = $q_preparada->fetch()){
        $evento = array('nombre_evento' => $res['nombre_evento'], 'icono' => $res['icono'],
        'id' => $res['id']);

        array_push($eventos, $evento);
      }
  
      return $eventos;
    }
  
    function getPalabrasCensuradas(){
      $palabras_censuradas = array();

      $q = "SELECT palabra FROM banned_words";
      $q_preparada = $this->mysqli->prepare($q);
      $q_preparada->execute();
  
      while($res = $q_preparada->fetch()){
        array_push($palabras_censuradas, $res['palabra']);
      }
  
      return $palabras_censuradas;
    }
  }
?>