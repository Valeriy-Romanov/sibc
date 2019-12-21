<?php
session_start();
require_once '../php/connect_db.php';
require_once '../php/function.php';

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) && empty($fname) && empty($role)) {
    logout();
}
if ($role < 5) {
    logout();
}

if (!empty($_POST['js_clnt_name'])) {
    $code = mysqli_real_escape_string($link, $_POST['js_clnt_name']);
    $query = "SELECT DISTINCT `invoice` FROM  `talons` WHERE `clients_name` = '$code'";
    $res = mysqli_query($link, $query);
    $data = '<option selected></option>';
    while ($row = mysqli_fetch_assoc($res)) {
        if (!empty($row['invoice'])) {
            $data .= "<option value='{$row['invoice']}'>{$row['invoice']}</option>";
        }
    }
    print_r($data);
} else {
    logout();
}
