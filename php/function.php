<?php
/////////////////заполняем главнй экран(для всех кроме операторов)//////////////////////////////////////
function getGiveReturn($fname, $link)
{
  $startDate = date("Y-m-d");
  $startDate .= " 00:00:00";
  $finishDate = date("Y-m-d");
  $finishDate .= " 23:59:59";
  $now = date("Y-m-d");

  $resultArr = array();
  $resultArrG = array();

  $number_0 = $number_1 = 0;
  $a9210_0 = $a9220_0 = $a9510_0 = $a9520_0 = $dt10_0 = $dt20_0 = $gaz10_0 = $gaz20_0 = 0;
  $a9210_1 = $a9220_1 = $a9510_1 = $a9520_1 = $dt10_1 = $dt20_1 = $gaz10_1 = $gaz20_1 = 0;

  $query = "SELECT `barcode` FROM `talons` 
                                        WHERE `fname_return`='$fname'
                                         AND (`date_return` BETWEEN '$startDate' AND '$finishDate')";
  $sql = mysqli_query($link, $query);
  $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
  if ($sql != false) {
    if (mysqli_affected_rows($link) > 0) {
      while ($row = mysqli_fetch_assoc($sql)) {
        array_push($resultArr, $row); //заполняем массив всеми найденными значениями
      }
    } else {
      goto showResult;
    }
  } else {
    print_r($err);
    return false;
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

  $queryg = "SELECT `barcode` FROM `talons` 
                                        WHERE `fname_give`='$fname'
                                         AND (`date_give` BETWEEN '$startDate' AND '$finishDate')";
  $sqlg = mysqli_query($link, $queryg);
  $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
  if ($sqlg != false) {
    if (mysqli_affected_rows($link) > 0) {
      while ($rowg = mysqli_fetch_assoc($sqlg)) {
        array_push($resultArrG, $rowg); //заполняем массив всеми найденными значениями
      }
    } else {
      goto showResult;
    }
  } else {
    print_r($err);
    return false;
  }
  // тут код подготовки к выводу отчета на экран
  for ($i = 0, $count = count($resultArrG); $i < $count; $i++) {
    switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
      case preg_match('~2092010[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $a9210_1 = $a9210_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2092020[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $a9220_1 = $a9220_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2095010[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $a9510_1 = $a9510_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2095020[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $a9520_1 = $a9520_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2055010[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $dt10_1 = $dt10_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2055020[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $dt20_1 = $dt20_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2078010[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $gaz10_1 = $gaz10_1 + 1;
        $number_1 = $number_1 + 1;
        break;
      case preg_match('~2078020[0-9]{6}~', $resultArrG["$i"]['barcode']):
        $gaz20_1 = $gaz20_1 + 1;
        $number_1 = $number_1 + 1;
        break;
    }
  }
  showResult: $show = "<span class=\"text text-center font-weight-bold text-danger\">За сегодня ($now) Вами ($fname) было ПОГАШЕНО талонов - <u>$number_0 шт</u> , из них:</span>
                      <div class=\"justify-content-center table-responsive w-75 p-3 no-m-p\">
                        <table class=\"table text-center text-danger mt-3 no-m-p\">
                          <thead>
                            <tr class=\"thead-main-table-custom\">
                              <th class=\"th-main-table text-danger\"></th>
                              <th class=\"th-main-table\">ДТ-10л</th>
                              <th class=\"th-main-table\">Аи92-10л</th>
                              <th class=\"th-main-table\">ГАЗ-10л</th>
                              <th class=\"th-main-table\">Аи95-10л</th>
                              <th class=\"th-main-table\">|</th>
                              <th class=\"th-main-table\">ДТ-20л</th>
                              <th class=\"th-main-table\">Аи92-20л
                              <th class=\"th-main-table\">ГАЗ-20л</th>
                              <th class=\"th-main-table\">Аи95-20л</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>штук</td>
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
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>";

  $show .= "<span class=\"text text-center font-weight-bold text-success mt-4 no-m-p\">За сегодня ($now) Вами ($fname) было ВЫДАНО талонов - <u>$number_1 шт</u> , из них:</span>
            <div class=\"justify-content-center table-responsive w-75 p-3 no-m-p\">
              <table class=\"table text-center text-success mt-3 no-m-p\">
                <thead>
                  <tr class=\"thead-main-table-custom\">
                    <th class=\"th-main-table text-danger\"></th>
                    <th class=\"th-main-table\">ДТ-10л</th>
                    <th class=\"th-main-table\">Аи92-10л</th>
                    <th class=\"th-main-table\">ГАЗ-10л</th>
                    <th class=\"th-main-table\">Аи95-10л</th>
                    <th class=\"th-main-table\">|</th>
                    <th class=\"th-main-table\">ДТ-20л</th>
                    <th class=\"th-main-table\">Аи92-20л
                    <th class=\"th-main-table\">ГАЗ-20л</th>
                    <th class=\"th-main-table\">Аи95-20л</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>штук</td>
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
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
              </table>
            </div>";
  print_r($show);
}
////////////////////////////////////////////////////////////////////////////

///////////////////////Заполняет главный экран(для операторов)/////////////
function getReturn($fname, $link)
{
  $startDate = date("Y-m-d");
  $startDate .= " 00:00:00";
  $finishDate = date("Y-m-d");
  $finishDate .= " 23:59:59";
  $now = date("Y-m-d");

  $resultArr = array();

  $number_0 = 0;
  $a9210_0 = $a9220_0 = $a9510_0 = $a9520_0 = $dt10_0 = $dt20_0 = $gaz10_0 = $gaz20_0 = 0;

  $query = "SELECT `barcode` FROM `talons` 
                                        WHERE `fname_return`='$fname'
                                         AND (`date_return` BETWEEN '$startDate' AND '$finishDate')";
  $sql = mysqli_query($link, $query);
  $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";
  if ($sql != false) {
    if (mysqli_affected_rows($link) > 0) {
      while ($row = mysqli_fetch_assoc($sql)) {
        array_push($resultArr, $row); //заполняем массив всеми найденными значениями
      }
    } else {
      goto showResult;
    }
  } else {
    print_r($err);
    return false;
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

  showResult: $show = "<span class=\"text text-center font-weight-bold text-danger\">За сегодня ($now) Вами ($fname) было ПОГАШЕНО талонов - <u>$number_0 шт</u> , из них:</span>
                      <div class=\"justify-content-center table-responsive w-75 p-3 no-m-p\">
                        <table class=\"table text-center text-danger mt-3 no-m-p\">
                          <thead>
                            <tr class=\"thead-main-table-custom\">
                              <th class=\"th-main-table text-danger\"></th>
                              <th class=\"th-main-table\">ДТ-10л</th>
                              <th class=\"th-main-table\">Аи92-10л</th>
                              <th class=\"th-main-table\">ГАЗ-10л</th>
                              <th class=\"th-main-table\">Аи95-10л</th>
                              <th class=\"th-main-table\">|</th>
                              <th class=\"th-main-table\">ДТ-20л</th>
                              <th class=\"th-main-table\">Аи92-20л
                              <th class=\"th-main-table\">ГАЗ-20л</th>
                              <th class=\"th-main-table\">Аи95-20л</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>штук</td>
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
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>";
  print_r($show);
}
///////////////////////////////////////////////////////////////////////////

////////////////////////получает список докумнтов "на основании"//////////..
function getInvoice()
{
  global $link;
  $code = mysqli_real_escape_string($link, $_POST['js_clnt_name']);
  $query = "SELECT DISTINCT `invoice` FROM  `talons` WHERE `clients_name` = '$code'";
  $res = mysqli_query($link, $query);
  $data = '<option selected></option>';
  while ($row = mysqli_fetch_assoc($res)) {
    $data .= "<option value='{$row['invoice']}'>{$row['invoice']}</option>";
  }
  return $data;
}
////////////////////////////////////////////////////////////////////////////////////

//////Функция возращающая массив со штрих-кодами/
function barcode_array($data)
{
  if (!empty($data)) {
    $str = preg_replace("~[^0-9]~", '', $data); //убираем из строки после сканирование все символы кроме цифр
    if (((iconv_strlen($str)) % 13) == 0) { //проверяем нет ли ошибок в последовательности
      $barcode_arr = preg_split("~[\s]+~", trim(chunk_split($str, 13, ' '))); //создаем массив, разбив строку по 13 символов
      return ($barcode_arr);
    } else {
      return ("<span class=\"alert\">Ошибка сканирования! Неверная последовательность, просканируйте еще раз.</span>");
    }
  } else {
    return ("<span class=\"alert\">Ошибка! Пустой массив...</span>");
  }
}


////////////Функция выход///////////////////////
function logout()
{
  $_SESSION = array();
  session_unset();
  session_destroy();

  ?>
        <script type="text/javascript">
          window.location = '/';
        </script>
      <?php
      }



      ?>