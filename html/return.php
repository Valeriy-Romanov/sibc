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

  $countRow = 50; // количество строк на странице
  // номер страницы
  if (!isset($_GET['page']) || $_GET['page'] < 1) {
    $pageNum = 1;
  } else {
    $pageNum = $_GET['page'];
  }
  $pageAdress = "http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?page=";
  $startIndex = ($pageNum - 1) * $countRow; // с какой записи начать выборку(-1 т.к. первый элемент массива это нулевой)
  $countAllRows = count($_SESSION['resultArr']); // получение полного количества строк
  $lastPage = ceil($countAllRows / $countRow); // номер последней страницы

  ////////выводим модальное окно///////
  if (!isset($_GET['page'])) {
    print_r($_SESSION['modalWindow']);
  }
?>
<!-- Begin head -->
<?php
require_once "../html/templates/head.php";
?>
<!--=================================================-->
<!-- Fixed navbar -->

<header>
  <?php
  require_once "../html/templates/menu.php";
  ?>
</header>
<!--=================================================-->
<!-- Begin page content -->

<main role="main" class="flex-shrink-0">
  <div class="container min-vh-100">
    <div class="row justify-content-center mt-4 no-m-p">
      <p class="text-center text-success">Всего помещено в базу(выдано) <?= $_SESSION['$number']; ?> шт талонов, из них:</p>
      <div class="justify-content-center table-responsive w-75 p-2 no-m-p">
        
        <table class="table text-center table-bordered table-sm no-m-p">
          <thead>
            <tr class="thead-main-table-custom">
              <th class="th-main-table "></th>
              <th class="th-main-table text-primary">ДТ-10л</th>
              <th class="th-main-table text-danger">Аи92-10л</th>
              <th class="th-main-table text-warning">ГАЗ-10л</th>
              <th class="th-main-table text-success">Аи95-10л</th>
              <th class="th-main-table ">|</th>
              <th class="th-main-table text-primary">ДТ-20л</th>
              <th class="th-main-table text-danger">Аи92-20л
              <th class="th-main-table text-warning">ГАЗ-20л</th>
              <th class="th-main-table text-success">Аи95-20л</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>штук</td>
              <td><span class="text-primary" id="dt10"><?= $_SESSION['dt10'] ?></span></td>
              <td><span class="text-danger" id="a9210"><?= $_SESSION['a9210'] ?></span></td>
              <td><span class="text-warning" id="gaz10"><?= $_SESSION['gaz10'] ?></span></td>
              <td><span class="text-success" id="a9510"><?= $_SESSION['a9510'] ?></span></td>
              <td>|</td>
              <td><span class="text-primary" id="dt20"><?= $_SESSION['dt20'] ?></span></td>
              <td><span class="text-danger" id="a9220"><?= $_SESSION['a9220'] ?></span></td>
              <td><span class="text-warning" id="gaz20"><?= $_SESSION['gaz20'] ?></span></td>
              <td><span class="text-success" id="a9520"><?= $_SESSION['a9520'] ?></span></td>
            </tr>
            <tr>
              <td>литров</td>
              <td><span class="text-primary" id="dt10"><?= $_SESSION['dt10'] * 10 ?></span></td>
              <td><span class="text-danger" id="a9210"><?= $_SESSION['a9210'] * 10 ?></span></td>
              <td><span class="text-warning" id="gaz10"><?= $_SESSION['gaz10'] * 10 ?></span></td>
              <td><span class="text-success" id="a9510"><?= $_SESSION['a9510'] * 10 ?></span></td>
              <td>|</td>
              <td><span class="text-primary" id="dt20"><?= $_SESSION['dt20'] * 10 ?></span></td>
              <td><span class="text-danger" id="a9220"><?= $_SESSION['a9220'] * 10 ?></span></td>
              <td><span class="text-warning" id="gaz20"><?= $_SESSION['gaz20'] * 10 ?></span></td>
              <td><span class="text-success" id="a9520"><?= $_SESSION['a9520'] * 10 ?></span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- главная таблиа результатов -->
    <div class="row mt-4 no-m-p">
      <div class="table-responsive mt-5">
        <table class="table on-center table-responsive table-bordered table-hover text-center mt-3" style="font-size: 0.8rem;">
          <thead class="thead-light">
            <tr>
              <th>Номер п/п</th>
              <th>Штрих-код</th>
              <th>Вид талона</th>
              <th>Наименование организации</th>
              <th>Дата выдачи</th>
              <th>статус операции</th>
            </tr>
          </thead>
          <tbody>
            <?php
            /////////вывод строк из массива///////////
            ////$startIndex - начало отбора. 
            ////$endIndex = $startIndex + $countRow - высчитываем последнюю строку
            ////$startIndex < $endIndex - пока начало меньше номера последней строки - работает цикл
            for ($startIndex, $endIndex = $startIndex + $countRow; $startIndex < $endIndex; $startIndex++) {
              print_r($_SESSION['resultArr']["$startIndex"]);
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<!--=================================================-->
<!-- Footer start -->

<?php
require_once "../html/templates/footer.php";
?>