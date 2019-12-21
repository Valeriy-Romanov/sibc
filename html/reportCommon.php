<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once '../php/connect_db.php';
require_once '../php/function.php';

//проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
    logout();
}

//обработка запроса на выход
if (isset($_GET["out"])) {
    unset($_GET["out"]);
    logout();
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="none" />
    <meta name=viewport content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!--===============================================================================================-->
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="../js/script.js"></script>
    <title>
        <?php
        $title = 'Отчет по клиентам || База данных "учет талонов"';
        print_r($title);
        ?>
    </title>
</head>

<body>
    <div class="main">
        <!-- начало шапки -->
        <?php
        require_once "../html/templates/header.php";
        ?>
        <!-- конец шапки, начало главной части -->
        <div class="conteiner">
            <div class="menu">
                <?php
                require_once "../html/templates/menu.php";
                ?>
            </div>
            <div class="conteiner-main">
                <div class="result">
                    <?php
                    if ($_SESSION['allTime']) {
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"total1\">За все время было ВЫДАНО талонов - 
                                                        <u>" . $_SESSION['numberTotal'] . " шт</u> , из них:</caption>
                                    <tbody>
                                        <tr id=\"total1\">
                                            <td></td>
                                            <td>ДТ-10л</td>
                                            <td>Аи92-10л</td>
                                            <td>ГАЗ-10л</td>
                                            <td>Аи95-10л</td>
                                            <td>|</td>
                                            <td>ДТ-20л</td>
                                            <td>Аи92-20л
                                            <td>ГАЗ-20л</td>
                                            <td>Аи95-20л</td>
                                        </tr>
                                        <tr id=\"total1\">
                                            <td>в штуках</td>
                                            <td>" . $_SESSION['dt10'] . "</td>
                                            <td>" . $_SESSION['a9210'] . "</td>
                                            <td>" . $_SESSION['gaz10'] . "</td>
                                            <td>" . $_SESSION['a9510'] . "</td>
                                            <td>|</td>
                                            <td>" . $_SESSION['dt20'] . "</td>
                                            <td>" . $_SESSION['a9220'] . "</td>
                                            <td>" . $_SESSION['gaz20'] . "</td>
                                            <td>" . $_SESSION['a9520'] . "</td>
                                        </tr>
                                    </tbody>
                            </table></fieldset><br>";
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"return1\">ПОГАШЕНО талонов - <u>" . $_SESSION['number_0'] . " шт</u> , из них:</caption>
                                <tbody>
                                    <tr id=\"return1\">
                                        <td></td>
                                        <td>ДТ-10л</td>
                                        <td>Аи92-10л</td>
                                        <td>ГАЗ-10л</td>
                                        <td>Аи95-10л</td>
                                        <td>|</td>
                                        <td>ДТ-20л</td>
                                        <td>Аи92-20л
                                        <td>ГАЗ-20л</td>
                                        <td>Аи95-20л</td>
                                    </tr>
                                    <tr id=\"return1\">
                                        <td>в штуках</td>
                                        <td>" . $_SESSION['dt10_0'] . "</td>
                                        <td>" . $_SESSION['a9210_0'] . "</td>
                                        <td>" . $_SESSION['gaz10_0'] . "</td>
                                        <td>" . $_SESSION['a9510_0'] . "</td>
                                        <td>|</td>
                                        <td>" . $_SESSION['dt20_0'] . "</td>
                                        <td>" . $_SESSION['a9220_0'] . "</td>
                                        <td>" . $_SESSION['gaz20_0'] . "</td>
                                        <td>" . $_SESSION['a9520_0'] . "</td>
                                    </tr>
                                </tbody>
                            </table></fieldset><br>";
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"give1\">На данный момент у клиентов \"НА РУКАХ\" талонов - 
                                                    <u>" . $_SESSION['number_1'] . " шт</u> , из них:</caption>
                                <tbody>
                                    <tr id=\"give1\">
                                        <td></td>
                                        <td>ДТ-10л</td>
                                        <td>Аи92-10л</td>
                                        <td>ГАЗ-10л</td>
                                        <td>Аи95-10л</td>
                                        <td>|</td>
                                        <td>ДТ-20л</td>
                                        <td>Аи92-20л
                                        <td>ГАЗ-20л</td>
                                        <td>Аи95-20л</td>
                                    </tr>
                                    <tr id=\"give1\">
                                        <td>в штуках</td>
                                        <td>" . $_SESSION['dt10_1'] . "</td>
                                        <td>" . $_SESSION['a9210_1'] . "</td>
                                        <td>" . $_SESSION['gaz10_1'] . "</td>
                                        <td>" . $_SESSION['a9510_1'] . "</td>
                                        <td>|</td>
                                        <td>" . $_SESSION['dt20_1'] . "</td>
                                        <td>" . $_SESSION['a9220_1'] . "</td>
                                        <td>" . $_SESSION['gaz20_1'] . "</td>
                                        <td>" . $_SESSION['a9520_1'] . "</td>
                                    </tr>
                                </tbody>
                            </table></fieldset><br>";
                        echo '<br>';
                    } else {
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"total1\">За период с " . $_SESSION['startDate'] . " по 
                                                            " . $_SESSION['finishDate'] . " было выдано талонов - 
                                                        <u>" . $_SESSION['numberTotal'] . " шт</u> , из них:</caption>
                                    <tbody>
                                        <tr id=\"total1\">
                                            <td></td>
                                            <td>ДТ-10л</td>
                                            <td>Аи92-10л</td>
                                            <td>ГАЗ-10л</td>
                                            <td>Аи95-10л</td>
                                            <td>|</td>
                                            <td>ДТ-20л</td>
                                            <td>Аи92-20л
                                            <td>ГАЗ-20л</td>
                                            <td>Аи95-20л</td>
                                        </tr>
                                        <tr id=\"total1\">
                                            <td>в штуках</td>
                                            <td>" . $_SESSION['dt10'] . "</td>
                                            <td>" . $_SESSION['a9210'] . "</td>
                                            <td>" . $_SESSION['gaz10'] . "</td>
                                            <td>" . $_SESSION['a9510'] . "</td>
                                            <td>|</td>
                                            <td>" . $_SESSION['dt20'] . "</td>
                                            <td>" . $_SESSION['a9220'] . "</td>
                                            <td>" . $_SESSION['gaz20'] . "</td>
                                            <td>" . $_SESSION['a9520'] . "</td>
                                        </tr>
                                    </tbody>
                            </table></fieldset><br>";
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"return1\">Всего за период с " . $_SESSION['startDate'] . " по 
                                                        " . $_SESSION['finishDate'] . " было ПОГАШЕНО талонов - 
                                                            <u>" . $_SESSION['number_0'] . " шт</u> , из них:</caption>
                                <tbody>
                                    <tr id=\"return1\">
                                        <td></td>
                                        <td>ДТ-10л</td>
                                        <td>Аи92-10л</td>
                                        <td>ГАЗ-10л</td>
                                        <td>Аи95-10л</td>
                                        <td>|</td>
                                        <td>ДТ-20л</td>
                                        <td>Аи92-20л
                                        <td>ГАЗ-20л</td>
                                        <td>Аи95-20л</td>
                                    </tr>
                                    <tr id=\"return1\">
                                        <td>в штуках</td>
                                        <td>" . $_SESSION['dt10_0'] . "</td>
                                        <td>" . $_SESSION['a9210_0'] . "</td>
                                        <td>" . $_SESSION['gaz10_0'] . "</td>
                                        <td>" . $_SESSION['a9510_0'] . "</td>
                                        <td>|</td>
                                        <td>" . $_SESSION['dt20_0'] . "</td>
                                        <td>" . $_SESSION['a9220_0'] . "</td>
                                        <td>" . $_SESSION['gaz20_0'] . "</td>
                                        <td>" . $_SESSION['a9520_0'] . "</td>
                                    </tr>
                                </tbody>
                            </table></fieldset><br>";
                        echo "<fieldset id=\"light\"><table class=\"total-table\">
                                <caption id=\"give1\">Из выданных за период с " . $_SESSION['startDate'] . " по 
                                                        " . $_SESSION['finishDate'] . ", осталось \"НА РУКАХ\"
                                                            талонов - <u>" . $_SESSION['number_1'] . " шт</u> , из них:</caption>
                                <tbody>
                                    <tr id=\"give1\">
                                        <td></td>
                                        <td>ДТ-10л</td>
                                        <td>Аи92-10л</td>
                                        <td>ГАЗ-10л</td>
                                        <td>Аи95-10л</td>
                                        <td>|</td>
                                        <td>ДТ-20л</td>
                                        <td>Аи92-20л
                                        <td>ГАЗ-20л</td>
                                        <td>Аи95-20л</td>
                                    </tr>
                                    <tr id=\"give1\">
                                        <td>в штуках</td>
                                        <td>" . $_SESSION['dt10_1'] . "</td>
                                        <td>" . $_SESSION['a9210_1'] . "</td>
                                        <td>" . $_SESSION['gaz10_1'] . "</td>
                                        <td>" . $_SESSION['a9510_1'] . "</td>
                                        <td>|</td>
                                        <td>" . $_SESSION['dt20_1'] . "</td>
                                        <td>" . $_SESSION['a9220_1'] . "</td>
                                        <td>" . $_SESSION['gaz20_1'] . "</td>
                                        <td>" . $_SESSION['a9520_1'] . "</td>
                                    </tr>
                                </tbody>
                            </table></fieldset><br>";
                        echo '<br>';
                    }
                    totalTable: echo '<fieldset id="light">';
                    print_r($_SESSION['thead']);

                    $countRow = 50; // количество строк на странице
                    // номер страницы
                    if (!isset($_GET['page']) || $_GET['page'] < 1) {
                        $pageNum = 1;
                    } else {
                        $pageNum = $_GET['page'];
                    }
                    $startIndex = ($pageNum - 1) * $countRow; // с какой записи начать выборку(-1 т.к. первый элемент массива это нулевой)
                    $countAllRows = count($_SESSION['resultArrTbl']); // получение полного количества строк
                    $lastPage = ceil($countAllRows / $countRow); // номер последней страницы

                    /////////вывод строк из массива///////////
                    ////$startIndex - начало отбора. 
                    ////$endIndex = $startIndex + $countRow - высчитываем последнюю строку
                    ////$startIndex < $endIndex - пока начало меньше номера последней строки - работает цикл
                    for ($startIndex, $endIndex = $startIndex + $countRow; $startIndex < $endIndex; $startIndex++) {
                        print_r($_SESSION['resultArrTbl']["$startIndex"]);
                    }

                    print_r($_SESSION['endTable']); ////завершающие тэги таблицы
                    echo '</ieldset>';
                    ?>
                    <br />
                </div>
                <div class="link">
                    <!-- вывод пагинатора -->
                    <p>
                        <ul>
                            <?php if ($pageNum > 1) { ?>
                                <li><a href=<?php echo "\"http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=1\""; ?>>&lt;&lt;</a></li>
                                <li><a href=<?php $pageNum1 = $pageNum - 1;
                                                echo "\"http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=$pageNum1\""; ?>>&lt;</a></li>
                            <?php } ?>

                            <?php for ($i = 1; $i <= $lastPage; $i++) { ?>
                                <li <?= ($i == $pageNum) ? 'class="current"' : ''; ?>> <a href=<?php echo "\"http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=$i\""; ?>><?= $i; ?></a> </li>
                            <?php } ?>

                            <?php if ($pageNum < $lastPage) { ?>
                                <li><a href=<?php $pageNum2 = $pageNum + 1;
                                                echo "\"http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=$pageNum2\""; ?>>&gt;</a></li>
                                <li><a href=<?php echo "\"http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=$lastPage\""; ?>>&gt;&gt;</a></li>
                            <?php } ?>
                        </ul>
                    </p>
                </div>


            </div>
        </div>
        <!-- конец главной части, начало "подвала" -->

        <?php
        require_once "../html/templates/footer.php";
        ?>

        <!-- конец "подвала" -->
    </div>

</body>

</html>