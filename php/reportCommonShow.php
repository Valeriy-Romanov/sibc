<?php
session_start();

///////////////////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
    logout();
}
if ($role < 5) {
    logout();
}
///////////////////////////////////////////////////////////////////////////////////////////

$startDate = $_POST['startDate'];
$finishDate = $_POST['finishDate'];
$all = $_POST['all'];

$resultArr = array();

$number_0 = $number_1 = $numberTotal = 0;
$a9210 = $a9220 = $a9510 = $a9520 = $dt10 = $dt20 = $gaz10 = $gaz20 = 0;
$a9210_0 = $a9220_0 = $a9510_0 = $a9520_0 = $dt10_0 = $dt20_0 = $gaz10_0 = $gaz20_0 = 0;
$a9210_1 = $a9220_1 = $a9510_1 = $a9520_1 = $dt10_1 = $dt20_1 = $gaz10_1 = $gaz20_1 = 0;

unset($_SESSION['allTime']);

if ($all == "allTime") {

    $_SESSION['allTime'] = true;

    $queryAll = "SELECT * FROM `talons` ORDER BY `date_give`";
    $sql = mysqli_query($link, $queryAll);

    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($sql != false) {
        if (mysqli_affected_rows($link) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                array_push($resultArr, $row); //заполняем массив всеми найденными значениями
            }
        } else {
            echo ("<span class=\"alert\">Искомых значений не найдено...попробуйте изменить фильтр выборки отчета.</span>");
            goto exitScript;
        }
    } else {
        print_r($err);
        goto exitScript;
    }

    $numberTotal = count($resultArr); //всего выдано талонов за все время этому клиенту

    for ($i = 0, $count = count($resultArr); $i < $count; $i++) {

        ///////////////////////////узнаем каких и сколько талонов было выдано за все время///////////////////////////////////////

        switch (true) {
            case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                $a9210 = $a9210 + 1;
                break;
            case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                $a9220 = $a9220 + 1;
                break;
            case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                $a9510 = $a9510 + 1;
                break;
            case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                $a9520 = $a9520 + 1;
                break;
            case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                $dt10 = $dt10 + 1;
                break;
            case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                $dt20 = $dt20 + 1;
                break;
            case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                $gaz10 = $gaz10 + 1;
                break;
            case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                $gaz20 = $gaz20 + 1;
                break;
        }

        ///////////////////////////находим общее кол-во "на руках" за все время///////////////////////////////////////
        if ($resultArr["$i"]['status'] == 1) {
            switch (true) {
                case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9210_1 = $a9210_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9220_1 = $a9220_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9510_1 = $a9510_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9520_1 = $a9520_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt10_1 = $dt10_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt20_1 = $dt20_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz10_1 = $gaz10_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz20_1 = $gaz20_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
            }
        }
        ///////////////////////////находим общее кол-во "погашенных" за все время талонов///////////////////////////////////////
        if ($resultArr["$i"]['status'] == 0) {
            switch (true) {
                case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9210_0 = $a9210_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9220_0 = $a9220_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9510_0 = $a9510_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9520_0 = $a9520_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt10_0 = $dt10_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt20_0 = $dt20_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz10_0 = $gaz10_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz20_0 = $gaz20_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
            }
        }
    }
    if (isset($_POST['exportToExcel'])) {
        //var_dump($resultArr);
        unset($_post['exportToExcel']);
        commonOfAllTimeExcel();
        goto exitScript;
    }
    ///////////////////////////////////////Выводим на экран результаты/////////////////////////////////////////////////

    $thead = '<table id="tbl">
				<thead>
                    <tr>
                        <th></th>
					    <th>Номер п/п</th>
						<th>Штрих-код</th>
						<th>Вид талона</th>
                        <th>Статус талона</th>
						<th>Наименование организации</th>
                        <th>Основание для выдачи</th>
                        <th>Кем выдан</th>
                        <th>Когда выдан</th>
						<th>Кем погашен</th>
                        <th>Когда погашен</th>
                        <th>Окончание срока годности</th>
					</tr>
				</thead>
                <tbody>';

    $resultArrTbl = array();

    for ($j = 0, $count_j = count($resultArr); $j < $count_j; $j++) {

        if ($resultArr["$j"]['status'] == 0) {
            $status = "ПОГАШЕН";
        } elseif ($resultArr["$j"]['status'] == 1) {
            $status = "НА РУКАХ";
        }

        $tmp = '<tr name="befor' . $resultArr["$j"]["talon_id"] . '" style="display: none;"></tr>
                    <tr name="main' . $resultArr["$j"]["talon_id"] . '">
                    <td>
                        <div class="adm-talon">
                            <div style="margin-right: 10px;">';
        if ($role > 7) {
            $tmp .= '<input type="checkbox" name="chck' . $resultArr["$j"]["talon_id"] . '" class="js-chck" id="' . $resultArr["$j"]["talon_id"] . '" >';
        }
        $tmp .= '</div>
                            <div class="adm-talon" id="btn-' . $resultArr["$j"]["talon_id"] . '" style="display: none;">
                                <div  style="margin-right: 5px;">
                                    <input type="image" src="../images/insert.png" id="' . $resultArr["$j"]["talon_id"] . '" name="js-updateTlns" alt="изменитиь" title="изменить">
                                </div>
                                <div style="margin-left: 5px;">
                                    <input type="image" src="../images/delete.png" id="' . $resultArr["$j"]["talon_id"] . '" name="js-deleteTlns" alt="удалить" title="удалить">
                                </div>
                            </div>
                        </div>
                    </td>        
                   	<td>' . $counterRow = count($resultArrTbl) + 1 . '</td>
                   	<td>' . $resultArr["$j"]["barcode"] . '<br></td>
                   	<td>' . $resultArr["$j"]["type"] . '</td>
                   	<td>' . $status . '</td>
                   	<td><span id="js-clntName-' . $resultArr["$j"]["talon_id"] . '" name="' . $resultArr["$j"]["clients_name"] . '">' . $resultArr["$j"]["clients_name"] . '</span></td>
                    <td><span id="js-invoice-' . $resultArr["$j"]["talon_id"] . '" name="' . $resultArr["$j"]["invoice"] . '">' . $resultArr["$j"]["invoice"] . '</span></td>
                    <td>' . $resultArr["$j"]["fname_give"] . '</td>   
                   	<td>' . $resultArr["$j"]["date_give"] . '</td>
                   	<td>' . $resultArr["$j"]["fname_return"] . '</td>   
                   	<td>' . $resultArr["$j"]["date_return"] . '</td>
                    <td>' . $resultArr["$j"]["date_off"] . '</td>
                    </div>
                </tr>';
        array_push($resultArrTbl, $tmp);
    }

    $endTable = '</tbody>
                   </table>';

    $_SESSION['thead'] = $thead;
    $_SESSION['resultArrTbl'] = $resultArrTbl;
    $_SESSION['endTable'] = $endTable;
    $_SESSION['startDate'] = $_POST['startDate'];
    $_SESSION['finishDate'] = $_POST['finishDate'];
    $_SESSION['numberTotal'] = $numberTotal;
    $_SESSION['dt10'] = $dt10;
    $_SESSION['a9210'] = $a9210;
    $_SESSION['gaz10'] = $gaz10;
    $_SESSION['a9510'] = $a9510;
    $_SESSION['dt20'] = $dt20;
    $_SESSION['a9220'] = $a9220;
    $_SESSION['gaz20'] = $gaz20;
    $_SESSION['a9520'] = $a9520;
    $_SESSION['number_0'] = $number_0;
    $_SESSION['dt10_0'] = $dt10_0;
    $_SESSION['a9210_0'] = $a9210_0;
    $_SESSION['gaz10_0'] = $gaz10_0;
    $_SESSION['a9510_0'] = $a9510_0;
    $_SESSION['dt20_0'] = $dt20_0;
    $_SESSION['a9220_0'] = $a9220_0;
    $_SESSION['gaz20_0'] = $gaz20_0;
    $_SESSION['a9520_0'] = $a9520_0;
    $_SESSION['number_1'] = $number_1;
    $_SESSION['dt10_1'] = $dt10_1;
    $_SESSION['a9210_1'] = $a9210_1;
    $_SESSION['gaz10_1'] = $gaz10_1;
    $_SESSION['a9510_1'] = $a9510_1;
    $_SESSION['dt20_1'] = $dt20_1;
    $_SESSION['a9220_1'] = $a9220_1;
    $_SESSION['gaz20_1'] = $gaz20_1;
    $_SESSION['a9520_1'] = $a9520_1;

    $showResult = "<script type=\"text/javascript\">
                                window.location = '../html/reportCommon.php'
                            </script>";

    print_r($showResult);

    goto exitScript;
}
///////////////////////////////////////////////////////////////////////////////////////////////

if (!empty($startDate) && !empty($finishDate)) {

    $startDate .= ' 00:00:00'; //приводим начальную дату к формату дата-время
    $finishDate .= ' 23:59:59'; //приводим начальную дату к формату дата-время

    $queryAll = "SELECT * FROM `talons` WHERE (`date_give` BETWEEN '$startDate' AND '$finishDate')
                                        OR (`date_return` BETWEEN '$startDate' AND '$finishDate')
                                        ORDER BY `date_give`";

    $sql = mysqli_query($link, $queryAll);

    $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

    if ($sql != false) {
        if (mysqli_affected_rows($link) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                array_push($resultArr, $row); //заполняем массив всеми найденными значениями
            }
        } else {
            echo ("<span class=\"alert\">Искомых значений не найдено...попробуйте изменить фильтр выборки отчета.</span>");
            goto exitScript;
        }
    } else {
        print_r($err);
        goto exitScript;
    }

    for ($i = 0, $count = count($resultArr); $i < $count; $i++) {

        ///////////////////////////находим общее кол-во выданных за период талонов///////////////////////////////////////
        if ($resultArr["$i"]['date_give'] >= $startDate && $resultArr["$i"]['date_give'] <= $finishDate) {
            switch (true) {
                case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9210 = $a9210 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9220 = $a9220 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9510 = $a9510 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9520 = $a9520 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt10 = $dt10 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt20 = $dt20 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz10 = $gaz10 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
                case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz20 = $gaz20 + 1;
                    $numberTotal = $numberTotal + 1;
                    break;
            }
        }
        ///////////////////////////находим общее кол-во "на руках" за период талонов///////////////////////////////////////
        if ($resultArr["$i"]['status'] == 1) {
            switch (true) {
                case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9210_1 = $a9210_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9220_1 = $a9220_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9510_1 = $a9510_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9520_1 = $a9520_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt10_1 = $dt10_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt20_1 = $dt20_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz10_1 = $gaz10_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
                case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz20_1 = $gaz20_1 + 1;
                    $number_1 = $number_1 + 1;
                    break;
            }
        }
        ///////////////////////////находим общее кол-во "погашенных" за период талонов///////////////////////////////////////
        if ($resultArr["$i"]['status'] == 0) {
            switch (true) {
                case preg_match('~2092010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9210_0 = $a9210_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2092020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9220_0 = $a9220_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2095010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9510_0 = $a9510_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2095020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $a9520_0 = $a9520_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2055010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt10_0 = $dt10_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2055020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $dt20_0 = $dt20_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2078010[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz10_0 = $gaz10_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
                case preg_match('~2078020[0-9]{6}~', $resultArr["$i"]['barcode']):
                    $gaz20_0 = $gaz20_0 + 1;
                    $number_0 = $number_0 + 1;
                    break;
            }
        }
    }

    if (isset($_POST['exportToExcel'])) {
        //var_dump($resultArr);
        unset($_post['exportToExcel']);
        commonOfPeriodExcel();
        goto exitScript;
    }
    ///////////////////////////////////////Выводим на экран результаты/////////////////////////////////////////////////

    $thead = '<table id="tbl">
				<thead>
                    <tr>
                        <th></th>
					    <th>Номер п/п</th>
						<th>Штрих-код</th>
						<th>Вид талона</th>
                        <th>Статус талона</th>
						<th>Наименование организации</th>
                        <th>Основание для выдачи</th>
                        <th>Кем выдан</th>
                        <th>Когда выдан</th>
						<th>Кем погашен</th>
                        <th>Когда погашен</th>
                        <th>Окончание срока годности</th>
					</tr>
				</thead>
                <tbody>';

    $resultArrTbl = array();

    for ($j = 0, $count_j = count($resultArr); $j < $count_j; $j++) {

        if ($resultArr["$j"]['status'] == 0) {
            $status = "ПОГАШЕН";
        } elseif ($resultArr["$j"]['status'] == 1) {
            $status = "НА РУКАХ";
        }

        $tmp = '<tr name="befor' . $resultArr["$j"]["talon_id"] . '" style="display: none;"></tr>
                    <tr name="main' . $resultArr["$j"]["talon_id"] . '">
                    <td>
                        <div class="adm-talon">
                            <div style="margin-right: 10px;">';
        if ($role > 7) {
            $tmp .= '<input type="checkbox" name="chck' . $resultArr["$j"]["talon_id"] . '" class="js-chck" id="' . $resultArr["$j"]["talon_id"] . '" >';
        }
        $tmp .= '</div>
                            <div class="adm-talon" id="btn-' . $resultArr["$j"]["talon_id"] . '" style="display: none;">
                                <div  style="margin-right: 5px;">
                                    <input type="image" src="../images/insert.png" id="' . $resultArr["$j"]["talon_id"] . '" name="js-updateTlns" alt="изменитиь" title="изменить">
                                </div>
                                <div style="margin-left: 5px;">
                                    <input type="image" src="../images/delete.png" id="' . $resultArr["$j"]["talon_id"] . '" name="js-deleteTlns" alt="удалить" title="удалить">
                                </div>
                            </div>
                        </div>
                    </td>
                   	<td>' . $counterRow = count($resultArrTbl) + 1 . '</td>
                   	<td>' . $resultArr["$j"]["barcode"] . '</td>
                   	<td>' . $resultArr["$j"]["type"] . '</td>
                   	<td>' . $status . '</td>
                   	<td><span id="js-clntName-' . $resultArr["$j"]["talon_id"] . '" name="' . $resultArr["$j"]["clients_name"] . '">' . $resultArr["$j"]["clients_name"] . '</span></td>
                    <td><span id="js-invoice-' . $resultArr["$j"]["talon_id"] . '" name="' . $resultArr["$j"]["invoice"] . '">' . $resultArr["$j"]["invoice"] . '</span></td>
                    <td>' . $resultArr["$j"]["fname_give"] . '</td>   
                   	<td>' . $resultArr["$j"]["date_give"] . '</td>
                   	<td>' . $resultArr["$j"]["fname_return"] . '</td>   
                   	<td>' . $resultArr["$j"]["date_return"] . '</td>
                   	<td>' . $resultArr["$j"]["date_off"] . '</td>
                   </tr>';
        array_push($resultArrTbl, $tmp);
    }

    $endTable = '</tbody>
               	</table>';

    $_SESSION['thead'] = $thead;
    $_SESSION['resultArrTbl'] = $resultArrTbl;
    $_SESSION['endTable'] = $endTable;
    $_SESSION['startDate'] = $_POST['startDate'];
    $_SESSION['finishDate'] = $_POST['finishDate'];
    $_SESSION['numberTotal'] = $numberTotal;
    $_SESSION['dt10'] = $dt10;
    $_SESSION['a9210'] = $a9210;
    $_SESSION['gaz10'] = $gaz10;
    $_SESSION['a9510'] = $a9510;
    $_SESSION['dt20'] = $dt20;
    $_SESSION['a9220'] = $a9220;
    $_SESSION['gaz20'] = $gaz20;
    $_SESSION['a9520'] = $a9520;
    $_SESSION['number_0'] = $number_0;
    $_SESSION['dt10_0'] = $dt10_0;
    $_SESSION['a9210_0'] = $a9210_0;
    $_SESSION['gaz10_0'] = $gaz10_0;
    $_SESSION['a9510_0'] = $a9510_0;
    $_SESSION['dt20_0'] = $dt20_0;
    $_SESSION['a9220_0'] = $a9220_0;
    $_SESSION['gaz20_0'] = $gaz20_0;
    $_SESSION['a9520_0'] = $a9520_0;
    $_SESSION['number_1'] = $number_1;
    $_SESSION['dt10_1'] = $dt10_1;
    $_SESSION['a9210_1'] = $a9210_1;
    $_SESSION['gaz10_1'] = $gaz10_1;
    $_SESSION['a9510_1'] = $a9510_1;
    $_SESSION['dt20_1'] = $dt20_1;
    $_SESSION['a9220_1'] = $a9220_1;
    $_SESSION['gaz20_1'] = $gaz20_1;
    $_SESSION['a9520_1'] = $a9520_1;

    $showResult = "<script type=\"text/javascript\">
	                 window.location = '../html/reportCommon.php'
                 </script>";

    print_r($showResult);
} else {
    echo ("<span class=\"alert\">Все поля обязательны к заполнению или поле \"Поиск по документ\" не долно быть пустым!</span>");
    goto exitScript;
}

exitScript:
