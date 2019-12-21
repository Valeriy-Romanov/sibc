<?php
session_start();
// require_once '../php/connect_db.php';
// require_once '../php/function.php';

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

$user_name = $_POST['user_name'];
$startDate = $_POST['startDate'];
$finishDate = $_POST['finishDate'];
$typeReport = $_POST['typeReport'];

$resultArr = array();
$resultArrRet = array();

$number_0 = $number_1 = 0;
$a9210_0 = $a9220_0 = $a9510_0 = $a9520_0 = $dt10_0 = $dt20_0 = $gaz10_0 = $gaz20_0 = 0;
$a9210_1 = $a9220_1 = $a9510_1 = $a9520_1 = $dt10_1 = $dt20_1 = $gaz10_1 = $gaz20_1 = 0;


if (!empty($user_name) && !empty($startDate) && !empty($finishDate) && !empty($typeReport)) {

    //////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////если формируем отчет по всем видам операций///////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////

    if ($typeReport == "all") {
        $startDate .= ' 00:00:00'; //приводим начальную дату к формату дата-время
        $finishDate .= ' 23:59:59'; //приводим начальную дату к формату дата-время
        /////формируем запрос/////
        $queryAll = "SELECT * FROM `talons` 
                        WHERE (`fname_give`='$user_name') 
                        AND (`date_give` BETWEEN '$startDate' AND '$finishDate')
                         ORDER BY `date_give`";
                        
        $sql = mysqli_query($link, $queryAll);
        $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
        if ($sql != false) {
            if (mysqli_affected_rows($link) > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    array_push($resultArr, $row); //заполняем массив всеми найденными значениями
                }
            } else {
                 $give = false;
            }
        } else {
            print_r($err);
            goto exitScript;
        }
        // тут код подготовки к выводу отчета на экран
        for ($i = 0, $count = count($resultArr); $i < $count; $i++) {
            
                switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
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
                /////////////////////////////////////////////////////////////////////////////
            $queryAll = "SELECT * FROM `talons` 
                            WHERE (`fname_return`='$user_name') 
                            AND (`date_return` BETWEEN '$startDate' AND '$finishDate')
                             ORDER BY `date_give`";
                            
            $sql = mysqli_query($link, $queryAll);
            $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
            if ($sql != false) {
                if (mysqli_affected_rows($link) > 0) {
                    while ($row = mysqli_fetch_assoc($sql)) {
                        array_push($resultArrRet, $row); //заполняем массив всеми найденными значениями
                    }
                } else {
                    $return = false;
                }
            } else {
                print_r($err);
                goto exitScript;
            }

            for ($i = 0, $count = count($resultArrRet); $i < $count; $i++) {
                switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
                    case preg_match('~2092010[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $a9210_0 = $a9210_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2092020[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $a9220_0 = $a9220_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2095010[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $a9510_0 = $a9510_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2095020[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $a9520_0 = $a9520_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2055010[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $dt10_0 = $dt10_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2055020[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $dt20_0 = $dt20_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2078010[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $gaz10_0 = $gaz10_0 + 1;
                        $number_0 = $number_0 + 1;
                        break;
                    case preg_match('~2078020[0-9]{6}~', $resultArrRet["$i"]['barcode']):
                        $gaz20_0 = $gaz20_0 + 1;
                        $number_0 = $number_0 + 1;
                        break; 
                }
            
            }

        if($give == true && $return == true){
            echo ("<span class=\"alert\">Искомых значений не найдено...попробуйте изменить фильтр выборки отчета.</span>");
            goto exitScript;
        }
        if(isset($_POST['exportToExcel'])){
            //var_dump($resultArr);
            unset($_post['exportToExcel']);
            allUserOperationsExcel();
            goto exitScript;
        }
        goto showResult;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////если формируем отчет по "ВЫДАНО"//////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////

    if ($typeReport == "give") {
        $startDate .= ' 00:00:00'; //приводим начальную дату к формату дата-время
        $finishDate .= ' 23:59:59'; //приводим начальную дату к формату дата-время
        /////формируем запрос/////
        $queryAll = "SELECT * FROM `talons` WHERE `fname_give`='$user_name' 
                                AND (`date_give` BETWEEN '$startDate' AND '$finishDate')
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
        // тут код подготовки к выводу отчета на экран
        for ($i = 0, $count = count($resultArr); $i < $count; $i++) {

            switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
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
        if(isset($_POST['exportToExcel'])){
            //var_dump($resultArr);
            unset($_post['exportToExcel']);
            giveUsersOperationsExcel();
            goto exitScript;
        }
        echo "<fieldset id=\"light\">
                <table class=\"total-table\">
                    <caption id=\"give\">Выдано ($user_name) за период (" . $_POST['startDate'] . " : " . $_POST['finishDate'] . ") талонов - <u>$number_1 шт</u> , из них:</caption>
                        <tbody>
                            <tr id=\"give\">
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
                            <tr id=\"give\">
                                <td>в штуках</td>
                                <td>$dt10_1</td>
                                <td>$a9210_1</td>
                                <td>$gaz10_1</td>
                                <td>$a9510_1</td>
                                <td>|</td>
                                <td>$dt20_1</td>
                                <td>$a9220_1</td>
                                <td>$gaz20_1</td>
                                <td>$a9520_1</td>
                            </tr>
                                            
                        </tbody>
                    </table></fieldset><br>";
        goto exitScript;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////если формируем отчет по "ПОГАШЕНО"//////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    if ($typeReport == "return") {
        $startDate .= ' 00:00:00'; //приводим начальную дату к формату дата-время
        $finishDate .= ' 23:59:59'; //приводим начальную дату к формату дата-время
        /////формируем запрос/////
        $queryAll = "SELECT * FROM `talons` WHERE `fname_return`='$user_name'
                        AND (`date_return` BETWEEN '$startDate' AND '$finishDate')
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
        // тут код подготовки к выводу отчета на экран
        for ($i = 0, $count = count($resultArr); $i < $count; $i++) {
            switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
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
        if(isset($_POST['exportToExcel'])){
            //var_dump($resultArr);
            unset($_post['exportToExcel']);
            returnUsersOperationsExcel();
            goto exitScript;
        }
        echo "<fieldset id=\"light\">
            <table class=\"total-table\">
                    <caption id=\"return\">Погашено ($user_name) за период (" . $_POST['startDate'] . " : " . $_POST['finishDate'] . ") талонов - <u>$number_0 шт</u> , из них:</caption>
                        <tbody>
                            <tr id=\"return\">
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
                            <tr id=\"return\">
                                <td>в штуках</td>
                                <td>$dt10_0</td>
                                <td>$a9210_0</td>
                                <td>$gaz10_0</td>
                                <td>$a9510_0</td>
                                <td>|</td>
                                <td>$dt20_0</td>
                                <td>$a9220_0</td>
                                <td>$gaz20_0</td>
                                <td>$a9520_0</td>
                            </tr>
                         </tbody>
                    </table></fieldset><br>";
        goto exitScript;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////

} else {
    echo ("<span class=\"alert\">Все поля обязательны к заполнению!!!</span>");
    goto exitScript;
}

showResult: echo "<fieldset id=\"light\">
            <table class=\"total-table\">
            <caption id=\"return\">Погашено ($user_name) за период (" . $_POST['startDate'] . " : " . $_POST['finishDate'] . ") талонов - <u>$number_0 шт</u> , из них:</caption>
                <tbody>
                    <tr id=\"return\">
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
                    <tr id=\"return\">
                        <td>в штуках</td>
                        <td>$dt10_0</td>
                        <td>$a9210_0</td>
                        <td>$gaz10_0</td>
                        <td>$a9510_0</td>
                        <td>|</td>
                        <td>$dt20_0</td>
                        <td>$a9220_0</td>
                        <td>$gaz20_0</td>
                        <td>$a9520_0</td>
                    </tr>
                </tbody>
        </table></fieldset><br>";
echo "<fieldset id=\"light\">
        <table class=\"total-table\">
			<caption id=\"give\">Выдано ($user_name) за период (" . $_POST['startDate'] . " : " . $_POST['finishDate'] . ") талонов - <u>$number_1 шт</u> , из них:</caption>
  				<tbody>
                    <tr id=\"give\">
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
      				<tr id=\"give\">
      					<td>в штуках</td>
      					<td>$dt10_1</td>
      					<td>$a9210_1</td>
      					<td>$gaz10_1</td>
      					<td>$a9510_1</td>
      					<td>|</td>
      					<td>$dt20_1</td>
      					<td>$a9220_1</td>
      					<td>$gaz20_1</td>
      					<td>$a9520_1</td>
      				</tr>
                </tbody>
        </table></fieldset><br>";
exitScript:
?>