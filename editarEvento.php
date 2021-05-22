<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $status = 1;
  if ($_SESSION['tipo'] != 'gestor'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }

  $BD = new BD();
  if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
    $infoEvento = $BD->getEvento($idEv);

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $fecha = $_POST['ffecha'];
        $titulo = $_POST['ftitle'];
        $organizador = $_POST['forganizador'];
        $cuerpo = $_POST['cuerpo'];
        $url = $_POST['fwebsite'];
        $nuevaInfoEvento = array('nombre_evento' => $titulo, 'fecha_evento' => $fecha,
            'organizador' => $organizador, 'descripcion' => $cuerpo, 'url' => $url,
            'id_evento' => $idEv);
        
        $status = $BD->editarEvento($nuevaInfoEvento);

        if ($status === 1){
            header("Location: editarEvento.php?ev=$idEv");
            exit();
        }        
    }
  }

  echo $twig->render('editarEvento.html', ['usuario' => $_SESSION['username'], 'evento' => $infoEvento ,'status' => $status]);
?>
