<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  if ($_SESSION['tipo'] != 'gestor'){
    http_response_code(403);
    die('No tienes acceso a esta sección');
  }


  function procesarImagen($tipo){
    $filename_res = '';
    $errors = array();
    $file_name = $_FILES[$tipo]['name'];
    $file_size = $_FILES[$tipo]['size'];
    $file_tmp = $_FILES[$tipo]['tmp_name'];
    $file_type = $_FILES[$tipo]['type'];
    $file_ext = strtolower(end(explode('.',$_FILES['fminiatura']['name'])));

    $extensions= array("jpeg","jpg","png");
    if (in_array($file_ext,$extensions) === false){
      $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
    }

    if ($file_size > 2097152){
      $errors[] = 'Tamaño del fichero demasiado grande';
    }

    if (empty($errors)==true) {
      $filename_res = $file_name;
      move_uploaded_file($file_tmp, "img/" . $file_name);
    }

    return $filename_res;
  }



  $BD = new BD();
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fecha = $_POST['ffecha'];
    $titulo = $_POST['ftitle'];
    $organizador = $_POST['forganizador'];
    $cuerpo = $_POST['cuerpo'];
    $url = $_POST['fwebsite'];
    



    //$imagenes = $_POST['fimagenes'];
    if(isset($_FILES['fminiatura'])){
      $path_miniatura = procesarImagen('fminiatura');
    }

    /*if(isset($_FILES['fimagenes'])){
      $path_miniatura = procesarImagen('fminiatura');
    }*/






    $datosEvento = array('fecha' => $fecha, 'nombre' => $titulo, 'organizador' => $organizador,
      'descripcion' => $cuerpo, 'url' => $url, 'miniatura' => $path_miniatura);

    $BD->addEvento($datosEvento);
  }

  echo $twig->render('crearEvento.html', ['usuario' => $_SESSION['username']]);
?>
