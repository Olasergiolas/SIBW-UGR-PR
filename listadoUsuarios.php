<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if ($_SESSION['tipo'] != 'superusuario'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }
  
  $BD = new BD();
  $contenido = '';
  $resultado_cambio = true;
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['fbtn'] === 'Filtrar'){
      $contenido = $_POST['fusuario'];
    }

    else if ($_POST['fbtn'] === 'Actualizar'){
      $usuario = $_POST['username'];
      $nuevo_tipo_usuario = $_POST["tipo_".$usuario];
      $resultado_cambio = $BD->setTipoUsuario($usuario, $nuevo_tipo_usuario);
    }
  }

  $listadoUsuarios = $BD->getUsuarios($contenido);
  $tiposUsuario = $BD->getTiposUsuario();

  echo $twig->render('listadoUsuarios.html', ['usuario' => $_SESSION['username'], 'tipo_usuario' => $_SESSION['tipo'],
  'usuarios' => $listadoUsuarios, 'tipos_usuario' => $tiposUsuario, 'status' => $resultado_cambio]);
?>