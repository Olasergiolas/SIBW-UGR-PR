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
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=SIBW", 'sergiogarcia', 'QVeApauxsYj3XcktpdnO');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo "Conexión fallida: " . $e->getMessage();
      }
      return $pdo;
    }
  
    //Obtener los comentarios de un evento
    function getComentarios($nombre_evento, $fecha_evento){
      $q = "SELECT id, usuario, fecha_hora, contenido, editado FROM comentarios
        WHERE nombre_evento = ? AND fecha_evento = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$nombre_evento, $fecha_evento]);

      $comentarios = array();
      while($res = $q_preparada->fetch()){
        $comentario = array('id' => $res['id'] ,'usuario' => $res['usuario'], 'fecha_hora' => $res['fecha_hora'],
        'contenido' => $res['contenido'], 'editado' => $res['editado']);

        array_push($comentarios, $comentario);
      }
  
      return $comentarios;
    }

    function getListadoCompletoComentarios($username=''){
      if ($username === ''){
        $q = "SELECT * FROM comentarios";
        $q_preparada = $this->pdo->prepare($q);
        $q_preparada->execute();
      }

      else{
        $q = "SELECT * FROM comentarios WHERE usuario=?";
        $q_preparada = $this->pdo->prepare($q);
        $q_preparada->execute([$username]);
      }    

      $comentarios = array();
      while($res = $q_preparada->fetch()){
        array_push($comentarios, $res);
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

    function registrarUsuario($datosUsuario){
      $res = 1;

      if (!filter_var($datosUsuario['mail'], FILTER_VALIDATE_EMAIL)){
        $res = -1;
      }

      if($res != -1){
        $q = "insert into usuarios(username, password, email, tipo) values(?, ?, ?, 'registrado')";
        $q_preparada = $this->pdo->prepare($q);
  
        try {
          $q_preparada->execute([$datosUsuario['username'], password_hash($datosUsuario['password'], PASSWORD_DEFAULT), $datosUsuario['mail']]);
        } catch (PDOException $e) {
          $res = -2;
        }
      }

      return $res;
    }

    function login($datosUsuario){
      $res = false;

      $q = "SELECT password from usuarios where username = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$datosUsuario['username']]);

      $res_query = $q_preparada->fetch();
      if (!empty($res_query)){
        $res = password_verify($datosUsuario['password'], $res_query['password']);
      }

      if ($res){
        $_SESSION['username'] = $datosUsuario['username'];
        $_SESSION['tipo'] = $this->getTipoUsuario($datosUsuario['username']);
      }

      return $res;
    }

    function getTipoUsuario($username){
      $q = "SELECT tipo from usuarios where username = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$username]);
      $res_query = $q_preparada->fetch();

      return $res_query['tipo'];
    }

    function getDatosUsuario($username){
      $q = "SELECT username, email, pfp, tipo from usuarios where username = ?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$username]);

      $res_query = $q_preparada->fetch();
      if (!empty($res_query)){
        $res = $res_query;
      }

      return $res;
    }

    function modificarUsuario($username, $datosUsuario){
      $res = true;

      $n_email = $datosUsuario['n_email'];
      $n_username = $datosUsuario['n_username'];
      if (!empty($datosUsuario['n_password'])){
        $n_password_hash = password_hash($datosUsuario['n_password'], PASSWORD_DEFAULT);

        $q = "UPDATE usuarios SET password=? where username=?";
        $q_preparada = $this->pdo->prepare($q);

        try {
          $q_preparada->execute([$n_password_hash, $username]);
        } catch (PDOException $e) {
          $res = false;
        }
      }

      if ($res === true){
        $q = "UPDATE usuarios SET username=?, email=? where username=?";
        $q_preparada = $this->pdo->prepare($q);
        try {
          $q_preparada->execute([$n_username, $n_email, $username]);
        } catch (PDOException $e) {
          $res = false;
        }
      } 
      
      return $res;
    }

    function addComentario($datosComentario){
      $q = "INSERT INTO comentarios(usuario, fecha_hora, contenido, nombre_evento, fecha_evento) VALUES(?, ?, ?, ?, ?)";
      $q_preparada = $this->pdo->prepare($q);

      $q_preparada->execute([$datosComentario['usuario'], $datosComentario['fecha_hora'], $datosComentario['contenido'],
      $datosComentario['nombre_evento'], $datosComentario['fecha_evento']]);
    }

    function eliminarComentario($idComentario){
      $q = "DELETE FROM comentarios WHERE id=?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$idComentario]);
    }

    function editarComentario($idComentario, $contenido){
      $q = "UPDATE comentarios SET editado=true, contenido=? WHERE id=?";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$contenido, $idComentario]);
    }

    function addEvento($datosEvento, $imagenes){
      $q = "INSERT INTO eventos(nombre_evento, fecha, organizador, descripcion, url, icono)
        VALUES (?, ?, ?, ?, ?, ?)";
      $q_preparada = $this->pdo->prepare($q);
      $q_preparada->execute([$datosEvento['nombre'], $datosEvento['fecha'], $datosEvento['organizador'],
      $datosEvento['descripcion'], $datosEvento['url'], $datosEvento['icono']]);


      foreach ($imagenes as $imagen) {
        $q = "INSERT INTO imagenes VALUES(?, ?, ?, ?)";
        $q_preparada = $this->pdo->prepare($q);
        $q_preparada->execute([$imagen['nombre_imagen'], $datosEvento['nombre'], $datosEvento['fecha'],
          $imagen['copyright']]);
      }
    }
  }

  function procesarPeticion($BD){
    if (isset($_GET['ev'])) {
      $idEv = $_GET['ev'];
    } else {
      $idEv = -1;
    }

    if (isset($_GET['borrarc'])) {
      if ($_SESSION['tipo'] === 'moderador'){
        $idComentarioBorrar = $_GET['borrarc'];
        $BD->eliminarComentario($idComentarioBorrar);
      }
    }
    
    $respuesta = array();
    if (is_numeric($idEv) == true){
      
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