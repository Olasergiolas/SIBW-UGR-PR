<?php
  class BD {
    private $pdo = NULL;

    public function __construct() {
      $this->pdo = $this->conectarBD();
    }

    function getEvento($idEv) {
      //Atributos del evento por defecto
      $nombreEvento = "Nombre por defecto";
      $fechaEvento = "1970/01/01";
      $organizador = "default";
      $descripcion = "default";
      $url = "default";
      $id = -1;
  
      //Atributos de la imagen principal del evento por defecto
      $imagen_principal = array('nombre_imagen' => "default_event.jpg",
      'copyright_imagen' => "default");

      //Petición de la información del evento
      $q = "SELECT id, nombre_evento, fecha, organizador, descripcion, url
        FROM eventos WHERE id = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$idEv]);
      $res = $q_preparada->fetch();
  
      //Datos del evento
      if (!empty($res)){
        $nombreEvento = $res['nombre_evento'];
        $fechaEvento = $res['fecha'];
        $organizador = $res['organizador'];
        $descripcion = $res['descripcion'];
        $url = $res['url'];
        $id = $res['id'];
      }
      
      //Darle formato al cuerpo del evento
      $descripcion_procesada = $this->procesarCuerpoEvento($descripcion);

      //Obtenemos las imágenes del evento
      $q = "SELECT nombre_imagen, copyright FROM imagenes
      WHERE nombre_evento = ? AND fecha = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$nombreEvento, $fechaEvento]);
      $res = $q_preparada->fetch();

      //Imagen principal del evento
      if (!empty($res)){
        $imagen_principal = array('nombre_imagen' => $res['nombre_imagen'],
        'copyright_imagen' => $res['copyright']);
      }

      //Resto de imágenes para añadirlas a la galería
      $imagenes_galeria = array();
      while($res = $q_preparada->fetch()){
        $imagen = array('nombre_imagen' => $res['nombre_imagen'],
          'copyright_imagen' => $res['copyright']);
        array_push($imagenes_galeria, $imagen);
      }
  
      $evento = array('id_evento' => $id, 'nombre_evento' => $nombreEvento,
      'fecha_evento' => $fechaEvento, 'organizador' => $organizador, 'descripcion' => $descripcion_procesada,
      'url' => $url, 'imagen_principal' => $imagen_principal, 'imagenes_galeria' => $imagenes_galeria);
  
      return $evento;
    }
  
    //Dar formato al cuerpo del evento
    function procesarCuerpoEvento($descripcion) {
      $descripcion_procesada = '<p class="event_body">';
      $descripcion_procesada .= str_replace("\n", '</p><p class="event_body">', $descripcion);
      $descripcion_procesada .= '</p>';
  
      return $descripcion_procesada;
    }
    
    //Conexión a la BD mediante PDO
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
  
    //Obtener los comentarios de un evento
    function getComentarios($nombre_evento, $fecha_evento){
      $q = "SELECT usuario, fecha_hora, contenido FROM comentarios
        WHERE nombre_evento = ? AND fecha_evento = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$nombre_evento, $fecha_evento]);

      $comentarios = array();
      while($res = $q_preparada->fetch()){
        $comentario = array('usuario' => $res['usuario'], 'fecha_hora' => $res['fecha_hora'],
        'contenido' => $res['contenido']);

        array_push($comentarios, $comentario);
      }
  
      return $comentarios;
    }
  
    //Obtener la información de los eventos que será mostrada en la portada
    function getEventosBriefing(){
      $eventos = array();

      $q = "SELECT nombre_evento, icono, id FROM eventos";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute();

      while($res = $q_preparada->fetch()){
        $evento = array('nombre_evento' => $res['nombre_evento'], 'icono' => $res['icono'],
        'id' => $res['id']);

        array_push($eventos, $evento);
      }
  
      return $eventos;
    }
  
    //Obtener listado de palabras censuradas
    function getPalabrasCensuradas(){
      $palabras_censuradas = array();

      $q = "SELECT palabra FROM banned_words";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute();
  
      while($res = $q_preparada->fetch()){
        array_push($palabras_censuradas, $res['palabra']);
      }
  
      return $palabras_censuradas;
    }
  }

  function procesarPeticion(){
    if (isset($_GET['ev'])) {
      $idEv = $_GET['ev'];
    } else {
      $idEv = -1;
    }
    
    $respuesta = array();
    if (is_numeric($idEv) == true){
      $BD = new BD();
      $evento = $BD->getEvento($idEv);
      $comentarios = $BD->getComentarios($evento['nombre_evento'],
        $evento['fecha_evento']);
      $palabras_censuradas = $BD->getPalabrasCensuradas();

      $respuesta = array('evento' => $evento, 'comentarios' => $comentarios,
        'palabras_censuradas' => $palabras_censuradas);
    }
  
    //Rechazamos la petición si ev no es un número
    else{
      http_response_code(400);
      die('Petición mal formada');
    }

    return $respuesta;
  }
?>