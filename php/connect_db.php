<?php
$dbhost = "localhost"; // Имя хоста БД
$dbusername = "rvaweb_sibc"; // Пользователь БД
$dbpass = "Vrmnv53@"; // Пароль к базе
$dbname = "rvaweb_sibc"; // Имя базы

$link = @mysqli_connect($dbhost, $dbusername, $dbpass, $dbname) or die("нет соединения с БД");
if (mysqli_connect_errno()) die(mysqli_connect_error());
if (!mysqli_set_charset($link, "utf8")) die(mysqli_error($link));

//удаляем переменную если ее кто-то попытается создать GET или POST запросом
//Сама же переменная с адресом нужна для того, что бы определить, идет ли авторизация с нашего сайта, а не с постороннего сервера.
if (isset($_GET['server_root'])) {
    $server_root = $_GET['server_root'];
    unset($server_root);
}
if (isset($_POST['server_root'])) {
    $server_root = $_POST['server_root'];
    unset($server_root);
}

$server_root = "http://silay-invest.loc/";
