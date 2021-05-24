<?php
  require_once "./vendor/autoload.php";
  include("BD.php");
  include("procesarImagen.php");

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
    $infoEvento['descripcion'] = strip_tags($infoEvento['descripcion']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $fecha = $_POST['ffecha'];
        $titulo = $_POST['ftitle'];
        $organizador = $_POST['forganizador'];
        $cuerpo = $_POST['cuerpo'];
        $url = $_POST['fwebsite'];
        $etiquetas = preg_split('/[\ \n\,]+/', $_POST['fetiquetas']);

        $nuevaInfoEvento = array('nombre_evento' => $titulo, 'fecha_evento' => $fecha,
            'organizador' => $organizador, 'descripcion' => $cuerpo, 'url' => $url,
            'id_evento' => $idEv);
        
        $status = $BD->editarEvento($nuevaInfoEvento);
        foreach ($etiquetas as $etiqueta) {
          $BD->addEtiquetaEvento($etiqueta, $idEv);
        }

        if(isset($_FILES['fminiatura'])){
            $path_miniatura = obtenerImagen();

            if(!empty($path_miniatura))
                $BD->editarMiniaturaEvento($idEv, $path_miniatura);
        }

        if(isset($_FILES['fimagenes'])){
            $imgs = obtenerImagenes();

            foreach ($imgs as $imagen) {
                if (!empty($imagen['nombre_imagen']))
                    $BD->addFotografiaEvento($nuevaInfoEvento, $imagen);
            }
        }

        if ($status === 1){
            header("Location: editarEvento.php?ev=$idEv");
            exit();
        }        
    }
  }

  echo $twig->render('editarEvento.html', ['usuario' => $_SESSION['username'], 'evento' => $infoEvento ,'status' => $status]);
?>
