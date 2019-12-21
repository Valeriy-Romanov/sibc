<?php
    session_start();
 
    require_once '../php/connect_db.php';
    require_once '../php/function.php';
        
   //////////проверка сессии и прав доступа///////////////////////////////////
    
    $user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
    $fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
    $role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
    if (empty($user_id) || empty($fname) || empty($role)){logout ();}
    if ($role < 3){logout ();} //если условие выполняеться - не пускает
    unset($_SESSION['resultArr']); //удаляем массив с результатами, если он был создан раннее
   
   //////////создаем массив со штрих-кодами///////////////////////////////

    if (!empty($_POST['barcode'])){
        $str = preg_replace("~[^0-9]~", '', $_POST['barcode']);   //убираем из строки после сканирование все символы кроме цифр
           
            if(((iconv_strlen($str)) % 13) == 0){   //проверяем нет ли ошибок в последовательности
             $barcode_arr = preg_split("~[\s]+~", trim(chunk_split($str, 13, ' ')));   //создаем массив, разбив строку по 13 символов
           }   
           else {
                echo "<span class=\"alert\">Ошибка сканирования! Неверная последовательность, просканируйте еще раз.</span>";
                goto endScript;
            }
    }
    else {
        echo "<span class=\"alert\">Ошибка! Пустой массив...</span>";
        goto endScript;
    }

    //////собираем данные для запроса//////////////////////////////////////
    
    if(!empty($_POST['returnDate'])){
        $date_return_to_update = $_POST['returnDate']; 
    } else {
        $date_return_to_update = date("Y-m-d H:i:s"); //дата выдачи(сегодняшняя)
    }

    //$date_return_to_update = date("Y-m-d H:i:s"); //дата гашения(сегодняшняя)
           
    $result = mysqli_query($link, "SELECT `barcode`, `type`, `clients_name`, `date_give`
                                    FROM `talons` WHERE `status` = '1'");
    $err = " " .mysqli_errno($link) . ": " . mysqli_error($link). "\n";
  
        if($result != false){ //проверяет успешен ли запрос
            $from_clients = array(); //пустой массив для всех кодов "на руках"
            $all_from_db = array(); //пустой массив для всех данных из выборки
                
                while($row = mysqli_fetch_assoc($result)){ 
                  array_push($from_clients, $row['barcode']); //заполнение массива значениями с базы
                  array_push($all_from_db, $row); //заполняем массив со всеми значениями из запроса 
                }
        }
        else{
          echo "<span class=\"alert\">Ошибка обработки sql-запроса(запрос на получение всех непогашенных талонов), погасить талоны не удалось, обратитесь к администратору...<br>Ниже выведен код ошибки, сообщите его администратору!<br><br>" . $err ."</span>"; 
          goto endScript;
        }

/////////рисуем шапку таблицы для вывода результатов операции//////////////////

/////////Делаем перебор(сравнение) входящих кодов с кодами из БД/////
$tmp_arr = array(); //массив-маркер найденого талона, для вариантов действия(запрос на гашение или "талон не найде")
$resultArr=array(); //результирующий массив(для вывода инфы об обработанных талонах)
$counterRow=0;////переменная хранящая номер по порядку записей в результирующей таблице

                for ($i = 0, $count = count($barcode_arr); $i < $count; $i++){
                    
                    for ($j = 0, $count_j = count($all_from_db); $j < $count_j; $j++){
                        
                        if ($barcode_arr["$i"] == $all_from_db["$j"]["barcode"]){
                            array_push($tmp_arr,$all_from_db["$j"]["barcode"]); //создаем массив с найденными кодами 
                        }
                    }
                    
                    if(in_array($barcode_arr["$i"], $tmp_arr) == false){ //проверяем есть ли проверенный код среди погашенных
                      ///////////////заносим в массив талоны не найденный в базе/////////
                      switch (true) { //определение типа талона
                                    case preg_match('~2092010[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон Аи-92 - 10л';
                                      break;
                                    case preg_match('~2092020[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон Аи-92 - 20л';
                                      break;
                                    case preg_match('~2095010[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон Аи-95 - 10л';
                                      break;
                                    case preg_match('~2095020[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон Аи-95 - 20л';
                                      break;
                                    case preg_match('~2055010[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон ДТ - 10л';
                                      break; 
                                    case preg_match('~2055020[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон ДТ - 20л';   
                                      break;
                                    case preg_match('~2078010[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон ГАЗ - 10л';
                                      break;
                                    case preg_match('~2078020[0-9]{6}~', $barcode_arr["$i"]):
                                      $typeOfTalon = 'Талон ГАЗ - 20л';
                                      break; 
                                    default:
                                    $unknownTalon='<tr class = "text-white bg-danger">
                                                    <td>' . $counterRow=count($resultArr)+1 . '</td>
                                                    <td>' . $barcode_arr["$i"] . '</td>
                                                    <td> ********** </td>
                                                    <td> ********** </td>
                                                    <td> ********** </td>
                                                    <td> Неизвестный талон!!!</td>
                                                   </tr>';
                                    array_push($resultArr, $unknownTalon);
                                    goto next_i;
                                    break;
                          }
                      $notReturnTalon='<tr class="text-white bg-danger">
                                        <td>' . $counterRow=count($resultArr)+1 . '</td>
                                        <td>' . $barcode_arr["$i"] . '</td>
                                        <td>' . $typeOfTalon . '</td>
                                        <td> ##########</td>
                                        <td> ##########</td>
                                        <td> Данный талон в базе не найден или уже погашен!!!</td>
                                      </tr>';
                      array_push($resultArr, $notReturnTalon);
                            next_i:
                    }  
                }
                ///////гасим талоны и выводим о них инфу////////////////////////

                $a9210 = $a9220 = $a9510 = $a9520 = $dt10 = $dt20 = $gaz10 = $gaz20 = 0; //присваиваем начальные значения для вывода в модальном окне
                $number = 0;
                
                if(!empty($tmp_arr)){
                  $barcode_to_update_str = implode(',',$tmp_arr);  ///выводим в строку массив с кодами для sql запроса
                  $query = mysqli_query($link, "UPDATE `talons` SET `status` = '0',
                                                                           `date_return` = '$date_return_to_update', 
                                                                           `fname_return` = '$fname'
                                                               WHERE `barcode` IN($barcode_to_update_str) and `status` = '1'"); 
                  $err = " " .mysqli_errno($link) . ": " . mysqli_error($link). "\n";
      
                    if($query != false){ //ввывод инф-ции о погашенных талонах...

                      $number = count($tmp_arr); //кол-во талонов завершивщих операцию успешно

                      for ($i = 0, $count = count($tmp_arr); $i < $count; $i++){

                          switch (true) { //счетаем сколько и каких талонов успешно завершили операцию 
                            case preg_match('~2092010[0-9]{6}~', $tmp_arr["$i"]):
                              $a9210 = $a9210 + 1;
                              break;
                            case preg_match('~2092020[0-9]{6}~', $tmp_arr["$i"]):
                              $a9220 = $a9220 + 1;
                              break;
                            case preg_match('~2095010[0-9]{6}~', $tmp_arr["$i"]):
                              $a9510 = $a9510 + 1;
                              break;
                            case preg_match('~2095020[0-9]{6}~', $tmp_arr["$i"]):
                              $a9520 = $a9520 + 1;
                              break;
                            case preg_match('~2055010[0-9]{6}~', $tmp_arr["$i"]):
                              $dt10 = $dt10 + 1;
                              break; 
                            case preg_match('~2055020[0-9]{6}~', $tmp_arr["$i"]):
                              $dt20 = $dt20 + 1;   
                              break;
                            case preg_match('~2078010[0-9]{6}~', $tmp_arr["$i"]):
                              $gaz10 = $gaz10 + 1;
                              break;
                            case preg_match('~2078020[0-9]{6}~', $tmp_arr["$i"]):
                              $gaz20 = $gaz20 + 1;
                              break;
                          }

                        for ($j = 0, $count_j = count($all_from_db); $j < $count_j; $j++){
                          
                          if ($tmp_arr["$i"] == $all_from_db["$j"]["barcode"]){
                            $successReturnTalon='<tr class="text-danger">
                                                  <td>' . $counterRow=count($resultArr)+1 . '</td>
                                                  <td>' . $all_from_db["$j"]["barcode"] . '</td>
                                                  <td>' . $all_from_db["$j"]["type"] . '</td>
                                                  <td>' . $all_from_db["$j"]["clients_name"] . '</td>
                                                  <td>' . $all_from_db["$j"]["date_give"] . '</td>
                                                  <td> Успешно погашен </td>
                                                 </tr>';
                            array_push($resultArr, $successReturnTalon); 
                          }
                        }
                      }
                    }
                    else{
                      echo "<span class=\"alert\">Ошибка обработки sql-запроса, погасить талоны не удалось, обратитесь к администратору...<br>Ниже выведен код ошибки, сообщите его администратору!<br><br>" . $err ."</span>";
                      goto endScript;
                    }
                  
                }    

next:
/************* формируем и выводим модальное окно с результатами операции ***********************/

                      $modalWindow="
                            <div class=\"modal-overlay1\" id=\"modal-overlay\"></div>
                            <div class=\"modal1\" id=\"modal\" aria-hidden=\"true\" aria-labelledby=\"modalTitle\" aria-describedby=\"modalDescription\" role=\"dialog\">
                              <button class=\"close-button1\" id=\"close-button\" title=\"Закрыть модальное окно\">Закрыть</button>
                              <div class=\"modal-guts1\" role=\"document\">
                                <br>
                                <h2>Было погашено $number шт талон(а\ов)!</h2>
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

$_SESSION['a9210']=$a9210;
$_SESSION['a9220']=$a9220;
$_SESSION['a9510']=$a9510;
$_SESSION['a9520']=$a9520;
$_SESSION['dt10']=$dt10;
$_SESSION['dt20']=$dt20;
$_SESSION['gaz10']=$gaz10;
$_SESSION['gaz20']=$gaz20;
$_SESSION['$number']=$number; //общее кол-во обработанных талонов(успешно)

$_SESSION['resultArr']=$resultArr;  //массив со строками таблицы вывода результата операции
$_SESSION['modalWindow']=$modalWindow;  //отрисовка модального окна
$showResult="<script type=\"text/javascript\">
                window.location = '../html/return.php'
            </script>";

print_r($showResult);
endScript:
unset($_POST['barcode']);                            
?>
