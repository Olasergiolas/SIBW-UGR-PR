<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if (isset($_SESSION['tipo'])){
    $tipo = $_SESSION['tipo'];
    $usuario = $_SESSION['username'];
  }
  else{
    $tipo = 'anonimo';
    $usuario = '';
  }

  $BD = new BD();

  $respuesta = procesarPeticion($BD);
  if (!is_null($respuesta)){
    $evento = $respuesta['evento'];
    $comentarios = $respuesta['comentarios'];
    $palabras_censuradas = $respuesta['palabras_censuradas'];
  }

  if (isset($_SESSION['username'])){
    $datosUsuario = $BD->getDatosUsuario($_SESSION['username']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      if($_POST['fbtn'] === 'Editar'){
        $idComentario = $_POST['fidComentario'];
        $contenido = $_POST['fcontenido'];
        $BD->editarComentario($idComentario, $contenido);
      }

      else{
        $contenido_comentario = $_POST['fcomment'];
        $mysqltime = date_create()->format('Y-m-d H:i:s');
        $datosComentario = array('usuario' => $_SESSION['username'], 'fecha_hora' => $mysqltime,
        'contenido' => $contenido_comentario, 'nombre_evento' => $evento['nombre_evento'],
        'fecha_evento' => $evento['fecha_evento']);
        $BD->addComentario($datosComentario);
      }

      $id_evento = $evento['id_evento'];
      $url = "Location: evento.php?ev=$id_evento";
      header($url);
      exit();
    }
  }



  echo $twig->render('evento.html', ['evento' => $evento, 'comentarios' => $comentarios, 
    'palabras_censuradas' => $palabras_censuradas, 'usuario' => $usuario,
    'email' => $datosUsuario['email'], 'tipo_usuario' => $tipo]);
?>
