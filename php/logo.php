<?php
session_start(); //стартуем сессию
header('Content-Type: text/html; charset=utf-8');
include '../php/connect_db.php';

//уничтожаем переменную с логином и паролем которые были созданы путем ввода их в строку   
if (isset($_GET['log'])) {
    $loginDB = $_GET['log'];
    unset($loginDB);
}
if (isset($_GET['pass'])) {
    $passDB = $_GET['pass'];
    unset($passDB);
}

//////////////////////////////////////////////////////////////
//аутентификация, проверка на переход только с формы//////////
//и проверка прав доступа с соответствующим перенаправлением//
//////////////////////////////////////////////////////////////

if (isset($_POST["log"]) && isset($_POST["pass"])) {

    $prov = getenv('HTTP_REFERER'); //определяем страницу с который пришел запрос
    $prov = str_replace("www.", "", $prov); //удаляем www если есть
    preg_match("/(http\:\/\/[-a-z0-9_.]+\/)/", $prov, $prov_pm); //чистим адресс от лишнего, нам необходимо добиться ссылки вот такого вида http://xxxx.ru
    $prov = $prov_pm[1]; //заносим чистый адрес в отдельную переменную
    $server_root = str_replace("www.", "", $server_root); //удаляем www если есть

    if ($server_root == $prov) { //если адрес нашего сайта и адрес страницы с которой был прислан запрос равны

        $loginForm = mysqli_real_escape_string($link, htmlspecialchars($_POST["log"]));

        $sql = mysqli_query($link, "SELECT * FROM `users` WHERE `login` = '$loginForm'") or die("хня с бд");
        $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
        $auth = mysqli_fetch_assoc($sql);
        if ($auth != false) {
            $passForm = md5($_POST['pass'] . $auth['solt']);
            if ($passForm == $auth['password'] and $loginForm == $auth['login']) {

                $_SESSION['user_id'] = $auth['user_id']; //создаем глобальную переменную с id пользователя
                $_SESSION['fname'] = $auth['fname']; //создаем глобальную переменную с ФИО пользователя
                $_SESSION['role'] = $auth['role']; //создаем глобальную переменную с role пользователя

                header("location: ../html/");
            } else {
                header("Location: ../"); //если пара логин и пароль не верены, редирект на форму
                // echo ("если пара логин и пароль не верены, редирект на форму");
            }
        } else {
            reload: header("Location: ../"); //если логин не верен, редирект на форму
            //echo ("если логин не верен, редирект на форму");
        }
    } else {
        header("Location: ../"); //если запустили скрипт не через форму
        //echo ("если запустили скрипт не через форм");
    }
} else {
    header("Location: ../"); //если запустили скрипт не через форму

}
