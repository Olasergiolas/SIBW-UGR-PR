<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $BD = new BD();
  $status = NULL;
  $mode = 'registro';

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      $mail = $_POST['fmail'];
      $username = $_POST['fname'];
      $password = $_POST['fpass'];

      session_start();
      $datosUsuario = array('username' => $username, 'password' => $password,
      'mail' => $password);
      $respuesta = $BD->registrarUsuario($datosUsuario);

      if ($respuesta === true){
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
      }

      else{
        $status = -1;
      }

      
  }

  echo $twig->render('identificacion.html', ['mode' => $mode, 'status' => $status]);
?>