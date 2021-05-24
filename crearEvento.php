<?php
  require_once "./vendor/autoload.php";
  include("BD.php");
  include("procesarImagen.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $status = 1;
  if ($_SESSION['tipo'] != 'gestor' and $_SESSION['tipo'] != 'superusuario'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }

  $BD = new BD();
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fecha = $_POST['ffecha'];
    $titulo = $_POST['ftitle'];
    $organizador = $_POST['forganizador'];
    $cuerpo = $_POST['cuerpo'];
    $url = $_POST['fwebsite'];
    $path_miniatura = obtenerImagen();
    $imgs = obtenerImagenes();

    $datosEvento = array('fecha' => $fecha, 'nombre' => $titulo, 'organizador' => $organizador,
      'descripcion' => $cuerpo, 'url' => $url, 'miniatura' => $path_miniatura);

    $res = $BD->addEvento($datosEvento, $imgs);

    if ($res){
      header("Location: index.php");
      exit();
    }

    else{
      $status = -1;
    }
  }

  echo $twig->render('crearEvento.html', ['usuario' => $_SESSION['username'], 'status' => $status, 'tipo_usuario' => $_SESSION['tipo']]);
?>
