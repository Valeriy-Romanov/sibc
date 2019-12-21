<?php
session_start();
require_once '../php/connect_db.php';
require_once '../php/function.php';
require_once '../PHPExcel/FunctionExcel.php';

///проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) && empty($fname) && empty($role)) {
    logout();
}
if ($role < 5) {
    logout();
}
?>

<form method="POST">
    <fieldset>
        <legend>
            <h2>Общий отчет по предприятию</h2>
        </legend>
        <fieldset id="light">
            <legend id="light">Период отчета</legend>
            <p>
                Начало периода:&nbsp
                <input type="text" id="date1" name="startDate" class="datepicker-here in" require readonly size="10" style="text-align: center;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                Конец периода:&nbsp
                <input type="text" id="date2" name="finishDate" class="datepicker-here in" require readonly size="10" style="text-align: center;">&nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp
                <input type="checkbox" id="js-all" name="all" value="allTime" onchange="allchk()"><label for="js-all">За все время</label>
            </p>
        </fieldset>
        <p>
            <input type="submit" name='printToMonitor' value="Отобразить на экране" id="btn">&nbsp&nbsp&nbsp
            <input type="submit" name='exportToExcel' value="Выгрузить в Excel" id="btn">
        </p>
    </fieldset>
</form>
<!--//////////////////////////////////////////-->
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['printToMonitor'])) {
    require_once "../php/reportCommonShow.php";
}
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exportToExcel'])) {
    require_once "../php/reportCommonShow.php";
}
?>