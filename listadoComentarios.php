<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if ($_SESSION['tipo'] != 'moderador'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }
  
  $BD = new BD();
  $listadoComentarios = $BD->getListadoCompletoComentarios();

  if (isset($_GET['borrarc'])) {
    $idComentarioBorrar = $_GET['borrarc'];
    $BD->eliminarComentario($idComentarioBorrar);
    header("Location: listadoComentarios.php");
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['fbtn'] === 'Editar'){
        $idComentario = $_POST['fidComentario'];
        $contenido = $_POST['fcontenido'];
        $BD->editarComentario($idComentario, $contenido);
        header("Location: listadoComentarios.php");
        exit();
    }
  }

  echo $twig->render('listadoComentarios.html', ['comentarios' => $listadoComentarios]);
?>