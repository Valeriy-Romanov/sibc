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
            <h2>Отчет по сотрудникам</h2>
        </legend>
        <fieldset id="light">
            <p>
                Выберите ФИО сотрудника:
                <?php
                echo '<select name ="user_name" required class="in">';
                echo '<option disabled selected>ФИО сотрудника</option>';
                $query = mysqli_query($link, "SELECT DISTINCT `fname_give` FROM `talons`");
                $name1 = array();
                while ($result = mysqli_fetch_assoc($query)) {
                    array_push($name1, $result['fname_give']);
                }
                $query = mysqli_query($link, "SELECT DISTINCT `fname_return` FROM `talons`");
                while ($result = mysqli_fetch_assoc($query)) {
                    if ($result['fname_return'] != NULL) {
                        array_push($name1, $result['fname_return']);
                    }
                }
                $name = array_values(array_unique($name1));
                for ($i = 0, $count = count($name); $i < $count; $i++) {
                    echo "<option value=\"$name[$i]\">$name[$i]</option>";
                }
                echo '</select><br></p>';
                ?>
        </fieldset>
        <p>
            <fieldset id="light">
                <legend id="light">Период отчета</legend>
                <p>
                    Начало периода:&nbsp
                    <input type="text" id="date1" name="startDate" class="datepicker-here in" require readonly size="10" style="text-align: center;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                    Конец периода:&nbsp
                    <input type="text" id="date2" name="finishDate" class="datepicker-here in" require readonly size="10" style="text-align: center;">
                </p>
            </fieldset>
        </p>
        <p>
            <fieldset id="light">
                <legend id="light">Критерии отчета (по каким операция формировать отчет)</legend>
                <p>
                    <input type="radio" name="typeReport" id="all" value="all" checked><label for="all">Все</label>&nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp
                    <input type="radio" name="typeReport" id="give" value="give"><label for="give">Выдано</label>&nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp
                    <input type="radio" name="typeReport" id="return" value="return"><label for="return">Погашено</label>&nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp
                </p>
            </fieldset>
        </p>
        <input type="submit" name='printToMonitor' value="Отобразить на экране" id="btn">&nbsp&nbsp&nbsp
        <input type="submit" name='exportToExcel' value="Выгрузить в Excel" id="btn">
    </fieldset>
</form>

<!--//////////////////////////////////////////-->
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['printToMonitor'])) {
    require_once "../php/reportUsersShow.php";
}
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exportToExcel'])) {
    require_once "../php/reportUsersShow.php";
}
?>