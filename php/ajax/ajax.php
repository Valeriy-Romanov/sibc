<?php
session_start();
require_once '../../php/connect_db.php';
require_once '../../php/function.php';

//////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role1 = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role1)) {
    logout();
}
if ($role1 < 9) {
    logout();
} //если условие выполняеться - не пускает

///////////////////////////////////////////////////////////////////////

//редактируем данные о пользователе.
if (isset($_POST['userId']) && isset($_POST['loginUser']) && isset($_POST['pswUser']) && isset($_POST['fioUser']) && isset($_POST['roleUser'])) {

    $id = mysqli_real_escape_string($link, htmlspecialchars($_POST['userId']));
    $login = mysqli_real_escape_string($link, htmlspecialchars($_POST['loginUser']));
    $fio = mysqli_real_escape_string($link, htmlspecialchars($_POST['fioUser']));
    $role = mysqli_real_escape_string($link, htmlspecialchars($_POST['roleUser']));
	$pass = $_POST['pswUser'];
  
    if (empty($_POST['pswUser'] || $_POST['pswUser'] = "")) {
        //пароль не меняем
        $query = "UPDATE `users` SET `login`='$login', `fname`='$fio', `role`='$role' WHERE `user_id`='$id'";
        $result = mysqli_query($link, $query);
        $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

        if ($result != false && mysqli_affected_rows($link) != 0) {
            $resp = true;
            echo ($resp);
            exit();
        } else {
            $resp = false;
            echo ($resp);
            exit();
        };
    } else {
        //пароль меняем
        $salt = '';
        $saltLength = 8; //длина соли
        for ($i = 0; $i < $saltLength; $i++) {
            $salt .= chr(mt_rand(33, 126)); //символ из ASCII-table
        }
        $saltPassword = md5($pass . $salt);
        $query = "UPDATE `users` SET `login`='$login', `password`='$saltPassword',
                         `solt`='$salt', `fname`='$fio', `role`='$role' WHERE `user_id`='$id'";
        $result = mysqli_query($link, $query);
        $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

        if ($result != false && mysqli_affected_rows($link) != 0) {
            $resp = 1;
            echo ($resp);
            exit();
        } else {
            $resp = 0;
            echo ($resp);
            exit();
        };
    };
};
//удаляем данные о пользователе
if (isset($_POST['deleteUser'])) {
    $id = mysqli_real_escape_string($link, htmlspecialchars($_POST['deleteUser']));
    $query = "DELETE FROM `users` WHERE `user_id`='$id'";
    $result = mysqli_query($link, $query);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($result != false && mysqli_affected_rows($link) != 0) {
        $resp = true;
        echo ($resp);
        exit();
    } else {
        $resp = false;
        echo ($resp);
        exit();
    };
};
//редактируем данные о клиенте
if (isset($_POST['clntId']) && isset($_POST['clntName'])) {
    sleep(2);
    $id = mysqli_real_escape_string($link, htmlspecialchars($_POST['clntId']));
    $clntName = mysqli_real_escape_string($link, htmlspecialchars($_POST['clntName']));

    $query = "UPDATE `clients` SET `clients_name`='$clntName' WHERE `clients_id`='$id'";
    $result = mysqli_query($link, $query);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($result != false && mysqli_affected_rows($link) != 0) {
        $resp = true;
        echo ($resp);
        exit();
    } else {
        $resp = false;
        echo ($resp);
        exit();
    };
}
//удаляем данные о клиенте
if (isset($_POST['deleteClnt'])) {
    $id = mysqli_real_escape_string($link, htmlspecialchars($_POST['deleteClnt']));
    $query = "DELETE FROM `clients` WHERE `clients_id`='$id'";
    $result = mysqli_query($link, $query);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($result != false && mysqli_affected_rows($link) != 0) {
        $resp = true;
        echo ($resp);
        exit();
    } else {
        $resp = false;
        echo ($resp);
        exit();
    };
};
//редактируем данные о талоне
if (isset($_POST['talon_id']) && isset($_POST['clients_name']) && isset($_POST['invoice'])) {
    sleep(2);
    $talon_id = mysqli_real_escape_string($link, htmlspecialchars($_POST['talon_id']));
    $clients_name = mysqli_real_escape_string($link, htmlspecialchars($_POST['clients_name']));
    $invoice = mysqli_real_escape_string($link, htmlspecialchars($_POST['invoice']));

    $clntNameFromBd = array();

    $queryClnt = "SELECT * FROM `clients`";
    $resClnt = mysqli_query($link, $queryClnt);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
    $qwe = '';
    if ($resClnt != false) {

        while ($row = mysqli_fetch_assoc($resClnt)) {
            array_push($clntNameFromBd, $row);
        }
    } else {
        echo ($err);
        exit();
    }
    $marker = '';

    for ($i = 0, $count_i = count($clntNameFromBd); $i < $count_i; $i++) {

        if ($clntNameFromBd[$i]['clients_name'] == $clients_name) {
            $marker = true;
        }
    }
    if ($marker == false) {

        exit("попытка внести неизвестного клиента! ");
    }

    $query = "UPDATE `talons` SET `clients_name`='$clients_name', `invoice`='$invoice' 
                    WHERE `talon_id`='$talon_id'";
    $result = mysqli_query($link, $query);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($result != false && mysqli_affected_rows($link) != 0) {
        $resp = true;
        echo ($resp);
        exit();
    } else {
        if( $err == 0){
            exit("Вы не внесли изменения в существующие данные.");
        }
        exit("ошибка запроса - " . $err . " обратитесь к администратору!");
    };
}
//удаляем данные о талоне
if (isset($_POST['deleteTlns'])) {
    $id = mysqli_real_escape_string($link, htmlspecialchars($_POST['deleteTlns']));
    $query = "DELETE FROM `talons` WHERE `talon_id`='$id'";
    $result = mysqli_query($link, $query);
    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($result != false && mysqli_affected_rows($link) != 0) {
        $resp = true;
        echo ($resp);
        exit();
    } else {
        $resp = false;
        echo ($resp);
        exit();
    };
};
