<?php
  require_once "./vendor/autoload.php";
  include("BD.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $BD = new BD();
  $mode = 'login';
  $status = NULL;

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      $username = $_POST['fname'];
      $password = $_POST['fpass'];
      $datosUsuario = array('username' => $username, 'password' => $password);

      session_start();
      $res = $BD->login($datosUsuario);
      if ($res){
        header("Location: index.php");
        exit();
      }
      else{
        $status = -1;
      }
  }

  echo $twig->render('identificacion.html', ['tipo_usuario' => $_SESSION['tipo'], 'mode' => $mode, 'status' => $status]);
?>