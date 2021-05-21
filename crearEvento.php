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

  function procesarImagen($datosImagen){
    $filename_res = '';
    $errors = array();

    $extensions= array("jpeg","jpg","png");
    if (in_array($datosImagen['file_ext'],$extensions) === false){
      $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
    }

    if ($datosImagen['file_size'] > 2097152){
      $errors[] = 'Tamaño del fichero demasiado grande';
    }

    if (empty($errors)==true) {
      $filename_res = $datosImagen['file_name'];
      move_uploaded_file($datosImagen['file_tmp'], "img/" . $datosImagen['file_name']);
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
    
    if(isset($_FILES['fminiatura'])){
      $imagen = $_FILES['fminiatura'];
      $file_name = $imagen['name'];
      $file_size = $imagen['size'];
      $file_tmp = $imagen['tmp_name'];
      $file_type = $imagen['type'];
      $file_ext = strtolower(end(explode('.',$imagen['name'])));
      $datosMiniatura = array('file_name' => $file_name, 'file_size' => $file_size, 'file_tmp' => $file_tmp,
        'file_type' => $file_type, 'file_ext' => $file_ext);

      $path_miniatura = procesarImagen($datosMiniatura);
    }

    if(isset($_FILES['fimagenes'])){
      $imgs = array();
      $total = count($_FILES['fimagenes']['name']);
      for ($i = 0; $i < $total; $i++){
        $imagen = $_FILES['fimagenes'];
        $file_name = $imagen['name'][$i];
        $file_size = $imagen['size'][$i];
        $file_tmp = $imagen['tmp_name'][$i];
        $file_type = $imagen['type'][$i];
        $file_ext = strtolower(end(explode('.',$imagen['name'][$i])));
        $datosImg = array('file_name' => $file_name, 'file_size' => $file_size, 'file_tmp' => $file_tmp,
          'file_type' => $file_type, 'file_ext' => $file_ext);

        $res = procesarImagen($datosImg);
        $img_copyright = array('nombre_imagen' => $res, 'copyright' => $organizador);
        array_push($imgs, $img_copyright);
      }
    }

    $datosEvento = array('fecha' => $fecha, 'nombre' => $titulo, 'organizador' => $organizador,
      'descripcion' => $cuerpo, 'url' => $url, 'miniatura' => $path_miniatura);

    $BD->addEvento($datosEvento, $imgs);
  }

  echo $twig->render('crearEvento.html', ['usuario' => $_SESSION['username']]);
?>
