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
if ($role < 5) {
  logout();
}
?>

<div class="row justify-content-center">
  <div class="find-form-wrapper bg-light mt-2">
    <h3 class="text-center mt-2 mb-3">Найти талон(ы)</h3>
    <form method="POST" onsubmit="return false" name="bc" role="form">
      <div class="form-group  pl-2 pr-2">
        <input type="text" name="js-barcode" id="js-barcode" pattern="^[ 0-9]+$" class="in form-control" minlength="13" maxlength="13" autocomplete="off" placeholder="Введите(отсканируйте) штрих-код" autofocus>
        <button hidden id="js-btn"></button>
      </div>
    </form>
    <form method="POST" role="form">
      <div class="form-group pl-2 pr-2">
        <input type="hidden" name="barcode" id="js-post"></input>
        <div class="form-group pl-2 pr-2">
          <label class="control-label" for="js-fileInput">Выберите файл-список штрих-кодов:</label>
          <input type="file" class="form-control-file btn btn-info text-custom" id="js-fileInput" onchange="fileRead(this.files)">
          <hr>
        </div>
        <div class="row">
          <div class="col">
            <input class="btn btn-success text-custom btn-block btn-sm" type="submit" name="find_talon_all" value="Найти талон(ы)" id="btn">
          </div>
          <div class="col text-right">
            <input class="btn btn-success text-custom btn-block btn-sm" type="submit" name="cansel" value="Отмена" for="bc" id="btn">
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="row justify-content-center">
  <div class="justify-content-center table-responsive w-75 p-3 no-m-p" id="js-table" hidden><br>
      <table class="table text-center mt-3 no-m-p">
        <thead>
          <tr class="thead-main-table-custom">
            <th class="th-main-table text-danger"></th>
            <th class="th-main-table">ДТ-10л</th>
            <th class="th-main-table">Аи92-10л</th>
            <th class="th-main-table">ГАЗ-10л</th>
            <th class="th-main-table">Аи95-10л</th>
            <th class="th-main-table">|</th>
            <th class="th-main-table">ДТ-20л</th>
            <th class="th-main-table">Аи92-20л
            <th class="th-main-table">ГАЗ-20л</th>
            <th class="th-main-table">Аи95-20л</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>штук</td>
            <td><span class="text-primary" id="dt10"></span></td>
            <td><span class="text-danger" id="a9210"></span></td>
            <td><span class="text-warning" id="gaz10"></span></td>
            <td><span class="text-success" id="a9510"></span></td>
            <td>|</td>
            <td><span class="text-primary" id="dt20"></span></td>
            <td><span class="text-danger" id="a9220"></span></td>
            <td><span class="text-warning" id="gaz20"></span></td>
            <td><span class="text-success" id="a9520"></span></td>
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
  </div>
</div>

<div class="row justify-content-center d-none d-md-flex">
  <div id="js-scan" style="font-size: 1.1em; display:block;"></div>
</div>
<!-- ------------------------------------------------------------------------------------------ -->
<div id="js-modal-window" hidden>
  <div class="modal-overlay1" id="modal-overlay"></div>
  <div class="modal1" id="modal" aria-hidden="true" aria-labelledby="modalTitle" aria-describedby="modalDescription" role="dialog">
    <button class="close-button1" id="close-button" title="Закрыть модальное окно">Закрыть</button>
    <div class="modal-guts1" role="document">
      <br>
      <p>
        <h1 id="js-modal-alert" style="color: #FF0000;"></h1>
      </p>
    </div>
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['find_talon_all'])) {
  require_once "../php/find-all.php";
}

?>