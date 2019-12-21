<?php
session_start();

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

/////////////////////////////////////////////////////////////////////
///подготовка переменных к формированию запроса добавления нового талона////

$client_name_to_insert = mysqli_real_escape_string($link, htmlspecialchars($_POST['clnt_name'])); //имя клиента из формы

$data_of_barcode = mysqli_real_escape_string($link, htmlspecialchars($_POST['barcode'])); // отсканированная последовательность из формы

if (!empty($data_of_barcode) && !empty($client_name_to_insert)) {
  $invoice_to_insert = mysqli_real_escape_string($link, htmlspecialchars($_POST['invoice'])); //название документа, на основании которого выдаються талоны

  if (empty($invoice_to_insert)) {
    $invoice_to_insert = NULL;
  } //если документ не указан присваиваем NULL

  $fname_to_insert = $fname; //ФИО выдающего талоны

  if (!empty($_POST['giveDate'])) {
    $date_give_to_insert = $_POST['giveDate'];
  } else {
    $date_give_to_insert = date("Y-m-d H:i:s"); //дата выдачи(сегодняшняя)
  }

  $date_off_to_insert =  date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 6, date("d") + 0, date("Y") + 0)); // дата окончания срока годности талона

  $barcodeFromScan = barcode_array($data_of_barcode);  // обработка штрих-кодов из поля ввода номера кода пользовательской функцией
} else {
  echo "<span class=\"alert\">Ошибка! Пустой массив...</span>";
  goto endScript;
}

///////////////////////////////////////////////////////////////////////////////////

if (!is_array($barcodeFromScan)) {   //проверяем что вернула функция barcоde_array, если не массив - выводим сообщение из функции и завершаем скрипт, в противном случаи продолжаем
  print_r($barcodeFromScan);
  goto endScript;
}

///////////////////////////////////////////////////////////////////////////////////
/////делаем выборку всех талонов "на руках", дабы отсечь дублирование талонов в базе

$result = mysqli_query($link, "SELECT * FROM `talons` WHERE `status` = 1");

$err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n"; //формируем код и пояснеие ошибки, в случаи неудачного запроса

if ($result == false) { //проверяет успешен ли запрос
  echo "<span class=\"alert\">Ошибка обработки sql-запроса(запрос на получение всех раннее выданных талонов), выдать талоны не удалось, попробуйте еще раз или обратитесь к администратору...<br>Ниже выведен код ошибки, сообщите его администратору!<br><br>" . $err . "</span>";
  goto endScript;
}

//////заполняем массив результатом запроса, если он усспешен///////////////////////

$all_from_db = array(); //пустой массив для всех данных из выборки

while ($row = mysqli_fetch_assoc($result)) {

  array_push($all_from_db, $row); //заполняем массив со всеми значениями из запроса
}

/////////////////////////////////////////////////////////////////////////////////
////перебираем массив со скана, выискивая неправильный формат штрих-кода////////

$unknownTalon = array(); //создаем пустой массив для неизвустных штрих-кодов 

for ($i = 0, $count = count($barcodeFromScan); $i < $count; $i++) {
  switch (true) { //определение типа талона, если не подходит ни один то дефаулт
    case preg_match('~2092010[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2092020[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2095010[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2095020[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2055010[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2055020[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2078010[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    case preg_match('~2078020[0-9]{6}~', $barcodeFromScan["$i"]):
      goto next_i;
      break;
    default:
      array_push($unknownTalon, $barcodeFromScan["$i"]); //заполняем массив неизвестными кодами
      goto next_i;
      break;
  }
  next_i:
}

/////////////////////////////////////////////////////////////////////////////////
////перебираем массив со скана, выискивая уже выданный талон и талон для выдачи/////

$onHandsTalon = array(); //создаем пустой массив для талонов "на руках"
$bcGiveTalon = array(); //создаем пустой массив для кодов в запрос на выдачу
for ($i = 0, $count = count($barcodeFromScan); $i < $count; $i++) {

  for ($j = 0, $count_j = count($all_from_db); $j < $count_j; $j++) {

    if ($barcodeFromScan["$i"] == $all_from_db["$j"]["barcode"]) { //проверяем есть ли данный талон среди "на руках"

      array_push($onHandsTalon, $all_from_db["$j"]); //заполняем массив с уже выданными талонами

      goto next_i2;
    }
  }
  if (in_array($barcodeFromScan["$i"], $unknownTalon)) {
    goto next_i2;
  }
  array_push($bcGiveTalon, $barcodeFromScan["$i"]); //заполняем массив кодами для выдачи 
  next_i2:
}

/////////////////////////////////////////////////////////////////////////////////
////если есть кода на выдачу - узнаем их тип и формирум массив для запроса/////

$a9210 = $a9220 = $a9510 = $a9520 = $dt10 = $dt20 = $gaz10 = $gaz20 = 0; //присваиваем начальные значения для вывода в модальном окне
$number = 0; // для подсчета успешно выданных талонов

if (empty($bcGiveTalon)) {
  goto modal;
} //если кодов на выдачу нет, то вызываем модальное окно и конец скрипта

for ($i = 0, $count = count($bcGiveTalon); $i < $count; $i++) {

  switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
    case preg_match('~2092010[0-9]{6}~', $bcGiveTalon["$i"]):
      $a9210 = $a9210 + 1;
      $typeOfTalon = 'Аи-92 - 10л';
      break;
    case preg_match('~2092020[0-9]{6}~', $bcGiveTalon["$i"]):
      $a9220 = $a9220 + 1;
      $typeOfTalon = 'Аи-92 - 20л';
      break;
    case preg_match('~2095010[0-9]{6}~', $bcGiveTalon["$i"]):
      $a9510 = $a9510 + 1;
      $typeOfTalon = 'Аи-95 - 10л';
      break;
    case preg_match('~2095020[0-9]{6}~', $bcGiveTalon["$i"]):
      $a9520 = $a9520 + 1;
      $typeOfTalon = 'Аи-95 - 20л';
      break;
    case preg_match('~2055010[0-9]{6}~', $bcGiveTalon["$i"]):
      $dt10 = $dt10 + 1;
      $typeOfTalon = 'ДТ - 10л';
      break;
    case preg_match('~2055020[0-9]{6}~', $bcGiveTalon["$i"]):
      $dt20 = $dt20 + 1;
      $typeOfTalon = 'ДТ - 20л';
      break;
    case preg_match('~2078010[0-9]{6}~', $bcGiveTalon["$i"]):
      $gaz10 = $gaz10 + 1;
      $typeOfTalon = 'ГАЗ - 10л';
      break;
    case preg_match('~2078020[0-9]{6}~', $bcGiveTalon["$i"]):
      $gaz20 = $gaz20 + 1;
      $typeOfTalon = 'ГАЗ - 20л';
      break;
  }
  ///собираем массив для таблицы///////////
  $arrGiveTalon["$i"] = array(
    'barcode' => "$bcGiveTalon[$i]",
    'type' => "$typeOfTalon",
    'clients_name' => "$client_name_to_insert",
    "invoice" => "$invoice_to_insert",
    "date_give" => " $date_give_to_insert",
    "fname_give" => "$fname_to_insert",
    "date_off" => "$date_off_to_insert"
  );

  ///наполняем переменную значениями для запроса//////////////////
  if (!isset($forQueryNewTalon)) {
    $forQueryNewTalon .= '(\'' . $arrGiveTalon["$i"]["barcode"] . '\', 
            					  \'' . $typeOfTalon . '\', 
            					  \'1\', 
            					  \'' . $client_name_to_insert . '\',
                        \'' . $invoice_to_insert . '\', 
                        \'' . $date_give_to_insert . '\', 
                        \'' . $fname_to_insert . '\', 
                        \'' . $date_off_to_insert . '\')'; // данные для запроса
  } else {
    $forQueryNewTalon .= ',(\'' . $arrGiveTalon["$i"]["barcode"] . '\', 
            					   \'' . $typeOfTalon . '\', 
            					   \'1\', 
            					   \'' . $client_name_to_insert . '\',
                         \'' . $invoice_to_insert . '\', 
                         \'' . $date_give_to_insert . '\', 
                         \'' . $fname_to_insert . '\', 
                         \'' . $date_off_to_insert . '\')'; // данные для запроса 
  }
}

///сам запрос на добавление в БД талонов////////////////////////////////////

if (isset($forQueryNewTalon)) {
  $query = mysqli_query($link, "INSERT INTO `talons` 
                                                      (`barcode`, `type`, `status`, `clients_name`, `invoice`, `date_give`, `fname_give`, `date_off`)
                                                      VALUES $forQueryNewTalon");
  $err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

  if ($query == false) {

    echo "<span class=\"alert\">Ошибка обработки sql-запроса(запрос на внесение новых талонов), выдать талоны не удалось, попробуйте еще раз или обратитесь к администратору...<br>Ниже выведен код ошибки, сообщите его администратору!<br><br>" . $err . "</span>";
    goto endScript;
  }
}

modal:

////формируем таблицу и выводим модальное окно/////

$resultArr = array(); //результирующий массив(для вывода инфы об обработанных талонах)
/////////рисуем шапку таблицы для вывода результатов операции//////////////////
if ($arrGiveTalon) {
  $number = count($arrGiveTalon);
} else {
  $number = 0;
}

if (isset($unknownTalon)) { ///заполняем результирующий массив строками с неизвестными талонами

  for ($j = 0, $count_j = count($unknownTalon); $j < $count_j; $j++) {


    $unknown = '<tr class = "text-white bg-danger">
                      	<td>' . $counterRow = count($resultArr) + 1 . '</td>
                      	<td>' . $unknownTalon["$j"] . '</td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> ********** </td>
                      	<td> Неизвестный талон!!!</td>
                     </tr>';
    array_push($resultArr, $unknown);
  }
}

if (isset($onHandsTalon)) { ///заполняем результирующий массив строками с выданными раннее талонами

  for ($j = 0, $count_j = count($onHandsTalon); $j < $count_j; $j++) {

    $not = '<tr class = "text-white bg-danger">
                   	<td>' . $counterRow = count($resultArr) + 1 . '</td>
                   	<td>' . $onHandsTalon["$j"]["barcode"] . '</td>
                   	<td>' . $onHandsTalon["$j"]["type"] . '</td>
                   	<td>Выдан раннее</td>
                   	<td>' . $onHandsTalon["$j"]["clients_name"] . '</td>
                   	<td>' . $onHandsTalon["$j"]["invoice"] . '</td>
                   	<td>' . $onHandsTalon["$j"]["date_give"] . '</td>
                   	<td>' . $onHandsTalon["$j"]["fname_give"] . '</td>
                   	<td>' . $onHandsTalon["$j"]["date_off"] . '</td>
                   	<td> Нельзя выдать(уже на руках)!!!</td>
                   </tr>';
    array_push($resultArr, $not);
  }
}

if (isset($arrGiveTalon)) { ///заполняем результирующий массив строками с выданными раннее талонами

  for ($j = 0, $count_j = count($arrGiveTalon); $j < $count_j; $j++) {

    $not = '<tr class = "text-success">
              <td>' . $counterRow = count($resultArr) + 1 . '</td>
              <td' . $counterRow = count($resultArr) + 1 . '</td>
              <td>' . $arrGiveTalon["$j"]["barcode"] . '</td>
              <td>' . $arrGiveTalon["$j"]["type"] . '</td>
              <td>На руках</td>
              <td>' . $arrGiveTalon["$j"]["clients_name"] . '</td>
              <td>' . $arrGiveTalon["$j"]["invoice"] . '</td>
              <td>' . $arrGiveTalon["$j"]["date_give"] . '</td>
              <td>' . $arrGiveTalon["$j"]["fname_give"] . '</td>
              <td>' . $arrGiveTalon["$j"]["date_off"] . '</td>
              <td>Талон успешно добавлен в базу!!!</td>
            </tr>';
    array_push($resultArr, $not);
  }
}

////////////////////////////////////////////////////////////////////////////////
/************* формируем и выводим модальное окно с результатами операции ***********************/

$modalWindow = "<div class=\"modal-overlay1\" id=\"modal-overlay\"></div>
                <div class=\"modal1\" id=\"modal\" aria-hidden=\"true\" aria-labelledby=\"modalTitle\" aria-describedby=\"modalDescription\" role=\"dialog\">
                  <button class=\"close-button1\" id=\"close-button\" title=\"Закрыть модальное окно\">Закрыть</button>
                  <div class=\"modal-guts1\" role=\"document\">
                    <br>
                    <h2>Было выдано $number шт талон(а\ов)!</h1>
                    <h3>Из них:</h3>
                    <p>Талон ДТ-10л ...... $dt10 шт<br>
                        Талон Аи92-10л .. $a9210 шт<br>
                        Талон ГАЗ-10л .... $gaz10 шт<br>
                        Талон Аи95-10л .. $a9510 шт<br>
                    </p>
                    <p>Талон ДТ-20л ...... $dt20 шт<br>
                        Талон Аи92-20л .. $a9220 шт<br>
                        Талон ГАЗ-20л .... $gaz20 шт<br>
                        Талон Аи95-20л .. $a9520 шт<br>
                    </p>
                  </div>
                </div>

                <script>
                  var modal = document.querySelector(\"#modal\"),
                  modalOverlay = document.querySelector(\"#modal-overlay\"),
                  closeButton = document.querySelector(\"#close-button\"),
                  openButton = document.querySelector(\"#open-button\");

                  closeButton.addEventListener(\"click\", function(){
                  modal.classList.toggle(\"closed\");
                  modalOverlay.classList.toggle(\"closed\");
                  });
                </script>";

$_SESSION['a9210'] = $a9210;
$_SESSION['a9220'] = $a9220;
$_SESSION['a9510'] = $a9510;
$_SESSION['a9520'] = $a9520;
$_SESSION['dt10'] = $dt10;
$_SESSION['dt20'] = $dt20;
$_SESSION['gaz10'] = $gaz10;
$_SESSION['gaz20'] = $gaz20;
$_SESSION['$number'] = $number; //общее кол-во обработанных талонов(успешно)

$_SESSION['resultArr'] = $resultArr;  //массив со строками таблицы вывода результата операции
$_SESSION['modalWindow'] = $modalWindow;  //отрисовка модального окна
$showResult = "<script type=\"text/javascript\">
	                 window.location = '../html/givetalon.php'
	             </script>";
unset($forQueryNewTalon);
unset($unknownTalon);
unset($onHandsTalon);
unset($arrGiveTalon);

print_r($showResult);

endScript:
