<?php
session_start();
require_once '../php/function.php';

//////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));

if (empty($user_id) || empty($fname) || empty($role)) {
  logout();
}

if ($role < 5) {
  logout();
}

/*Проверяем, есть ли клиент с таким именем в базе*/

if (!empty($_POST['clnt_name'])) {
  $clnt_name = mysqli_real_escape_string($link, htmlspecialchars($_POST['clnt_name']));
  $clientsFromBD_querty = "SELECT `clients_name` FROM `clients`";
  $sql = mysqli_query($link, $clientsFromBD_querty);
  $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

  if ($sql != false) {
    while ($row = mysqli_fetch_assoc($sql)) {
      if ($row['clients_name'] == $clnt_name) {
        echo '<span class="text-center text-danger h4 mt-3">Клиент с названем "' . $clnt_name . '" в базе уже существует, добавление невозможно!</span>';
        goto next;
      }
    }
    /**добавляем нового клиента*/
    $new_client_querty = "INSERT INTO `clients` (`clients_name`) VALUES ('$clnt_name')";
    $sql = mysqli_query($link, $new_client_querty);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($sql != false) {
      echo '<span class="text-center text-success h4 mt-3">Клиент "' . $clnt_name  . '" успешно добавлен!</span>';
      goto next;
    } else {
      echo ("$err");
      goto next;
    }
  } else {
    echo ("$err");
    goto next;
  }
} else {
  echo '<span class="text-center text-danger h4 mt-3">Ошибка! Не заполнено название клиента!</span>';
}
next: $_POST = array();
