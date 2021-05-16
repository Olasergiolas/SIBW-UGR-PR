<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $BD = new BD();

  if (isset($_SESSION['tipo'])){
    $tipo = $_SESSION['tipo'];
    $usuario = $_SESSION['username'];
  }
  else{
    $tipo = 'anonimo';
    $usuario = '';
  }

  if (isset($_SESSION['username'])){
    $usuario = $BD->getDatosUsuario($_SESSION['username']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $n_username = $_POST['fname'];
        $n_email = $_POST['fmail'];
        $n_password = $_POST['fpass'];
        $datosUsuario = array('n_username' => $n_username, 'n_email' => $n_email, 'n_password' => $n_password);
    
        $res = $BD->modificarUsuario($usuario['username'], $datosUsuario);

        if ($res === true){
          $_SESSION['username'] = $n_username;
          $usuario = $BD->getDatosUsuario($n_username);
          $status = 'modified';
        }

        else {
          $status = 'error';
        }
    }
  }
  
  echo $twig->render('perfil.html', ['usuario' => $usuario['username'], 'tipo_usuario' => $tipo, 'datos' => $usuario,
   'status' => $status]);
?>