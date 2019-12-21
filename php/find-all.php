<?php
session_start();
require_once '../php/function.php';

//////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
  logout();
}
if ($role < 3) {
  logout();
}

//////////создаем массив со штрих-кодами///////////////////////////////
$data  = mysqli_real_escape_string($link, htmlspecialchars($_POST['barcode']));
if (!empty($data)) {
  $str = preg_replace("~[^0-9]~", '', $data);   //убираем из строки после сканирование все символы кроме цифр
  if (((iconv_strlen($str)) % 13) == 0) {   //проверяем нет ли ошибок в последовательности
    $barcode_arr = preg_split("~[\s]+~", trim(chunk_split($str, 13, ' ')));   //создаем массив, разбив строку по 13 символов
  } else {
    echo 'Error, scan the barcode again...';
    goto next;
  }
} else {
  echo 'Error, array with barcode is empty...';
  goto next;
}

//////////Получаем ассоциотивный массив из ДБ со статусом "на руках" (status = 1)

$query = mysqli_query($link, "SELECT * FROM `talons`");
$err = " " . mysqli_errno($link) . ": " . mysqli_error($link) . "\n";

if ($query != false) { //проверяет успешен ли запрос
  $from_bd = array(); //пустой массив для всех кодов "на руках"
  while ($row = mysqli_fetch_assoc($query)) { //заполнение массива значениями с базы
    array_push($from_bd, $row);
  }
} else {
  echo $err;
}

//////////Перебираем элементы полученных массивов и совпадения записываем в результирующий///
$find_arr = array(); //создаем пустой массив для результатов срванения(найденные талоны в базе)
$barcode_from_bd = array(); //создаем пустой массив для результатов выборки(в массиве будут только штрих-кода)    
for ($i = 0, $count = count($barcode_arr); $i < $count; $i++) {

  for ($j = 0, $count_j = count($from_bd); $j < $count_j; $j++) {

    array_push($barcode_from_bd, $from_bd["$j"]["barcode"]); //создаем массив только со штрих кодами из базы

    if ($barcode_arr["$i"] == $from_bd["$j"]["barcode"]) {
      array_push($find_arr, $from_bd["$j"]); //создаем массив с найденными кодами 
    }
  }
}

////////// Проверяем нашлись ли совпадения, если нет выводим сообщение  "талон(ы) в базе не найдены"
if (empty($find_arr)) {
  echo "<div class=\"mt-3\"><span class=\"text-danger\">Талон(ы) в базе не найден(ы)...</span></div>";
  goto next;
}

/////////Проверяем остались ли ненайденный талоны,
/////////если нет, то выводим результат поиска в таблице...
$not_find_arr = array_diff($barcode_arr, $barcode_from_bd); //массив с ненайденными талонами
if (empty($not_find_arr)) {
  //table with find result
  echo '<div class="justify-content-center table-responsive w-100">
           <table class="table table-bordered table-sm table-hover text-center mt-3" style="font-size: 0.8rem;">
					   <thead class="thead-light">
               <tr>
                 <th>Штрих-код талона</th>
                 <th>Вид талона</th>
                 <th>Статус</th>
                 <th>Название организации</th>
                 <th>Основание для выдачи</th>
                 <th>Дата выдачи</th>
                 <th>ФИО выдавшего</th>
                 <th>Дата гашения</th>
                 <th>ФИО погасившего</th>
                 <th>Дата окончания годности</th>
               </tr>
             </thead>
             <tbody>';
  foreach ($find_arr as $key => $value) {
    /********************************************/
    if ($find_arr["$key"]["status"] == 1) {
      $status = 'выдан';
      $color = "text-success";
    } else {
      $status = 'погашен';
      $color = "text-danger";
    }
    /********************************************/
    echo '<tr class="' . $color . '">
           <td>' . $find_arr["$key"]["barcode"] . '</td>
           <td>' . $find_arr["$key"]["type"] . '</td>
           <td>' . $status . '</td>
           <td>' . $find_arr["$key"]["clients_name"] . '</td>
           <td>' . $find_arr["$key"]["invoice"] . '</td>
           <td>' . $find_arr["$key"]["date_give"] . '</td>
           <td>' . $find_arr["$key"]["fname_give"] . '</td>
           <td>' . $find_arr["$key"]["date_return"] . '</td>
           <td>' . $find_arr["$key"]["fname_return"] . '</td>
           <td>' . $find_arr["$key"]["date_off"] . '</td>
         </tr>';
  }
  echo '  </tbody>
        </table>
      </div>';

  goto next;
} else {
  foreach ($not_find_arr as $key => $value) { //выводим не найденные талоны
    // table with not find talon`s
    echo "<div class=\"mt-3\"><p class=\"text-danger\">Талон $value в базе не найден (не выдавался)</p></div>";
  }
  unset($value);

  //////////выводим найденные талоны
  echo '<div class="justify-content-center table-responsive w-100">
           <table class="table table-bordered table-sm table-hover text-center mt-3" style="font-size: 0.8rem;">
					   <thead class="thead-light">
               <tr>
                 <th>Штрих-код талона</th>
                 <th>Вид талона</th>
                 <th>Статус</th>
                 <th>Название организации</th>
                 <th>Основание для выдачи</th>
                 <th>Дата выдачи</th>
                 <th>ФИО выдавшего</th>
                 <th>Дата гашения</th>
                 <th>ФИО погасившего</th>
                 <th>Дата окончания годности</th>
               </tr>
             </thead>
             <tbody>';
  foreach ($find_arr as $key => $value) {
    /********************************************/
    if ($find_arr["$key"]["status"] == 1) {
      $status = 'выдан';
      $color = "text-success";
    } else {
      $status = 'погашен';
      $color = "text-danger";
    }
    /********************************************/
    echo '<tr class="' . $color . '">
           <td>' . $find_arr["$key"]["barcode"] . '</td>
           <td>' . $find_arr["$key"]["type"] . '</td>
           <td>' . $status . '</td>
           <td>' . $find_arr["$key"]["clients_name"] . '</td>
           <td>' . $find_arr["$key"]["invoice"] . '</td>
           <td>' . $find_arr["$key"]["date_give"] . '</td>
           <td>' . $find_arr["$key"]["fname_give"] . '</td>
           <td>' . $find_arr["$key"]["date_return"] . '</td>
           <td>' . $find_arr["$key"]["fname_return"] . '</td>
           <td>' . $find_arr["$key"]["date_off"] . '</td>
         </tr>';
  }
  echo '  </tbody>
        </table>
      </div>';

  unset($value);
  next: unset($_POST['barcode']);
  unset($_POST['find_talon_oprt']);
  mysqli_close($link);
}
