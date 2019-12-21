<?php
session_start();

//////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
  logout();
}
if ($role < 9) {
  logout();
} //если условие выполняеться - не пускает

$query = "SELECT * FROM `clients`";
$sql = mysqli_query($link, $query) or die("нет соединения с БД");

if ($sql != false) {
  while ($row = mysqli_fetch_assoc($sql)) {
    ?>
    <div class="form-row justify-content-center align-items-center mt-1 mb-0">

      <div id="p-<?= $row['clients_id'] ?>" class="form-group client-update bg-light w-75 py-3 px-1 mt-2 mb-3 no-m-p">
        <h4 id="h4-<?= $row['clients_id'] ?>" class="text-center"><?= $row['clients_name'] ?></h4>
        <div id="<?= $row['clients_id'] ?>" hidden></div>
        <div class="clientUpdate" name="<?= $row['clients_id'] ?>">
          <div class="form-row px-2 mb-1 mb-sm-0 align-items-center">
            <div class="col">
              <div class="form-check text-center">
                <input class="form-check-input" type="checkbox" id="chckb-<?= $row['clients_id'] ?>" onchange="showButtonClnt(<?= $row['clients_id'] ?>)">
                <label for="chckb-<?= $row['clients_id'] ?>" class="form-check-label">Редактировать</label>
              </div>
            </div>
            <div class="col">
              <div class="form-group text-center py-0 mb-0">
                <input class="form-control" required readonly type="text" id="name-<?= $row['clients_id'] ?>" name="clnt_name" value="<?= $row['clients_name'] ?>" autocomplete="off">
              </div>
            </div>
            <div class="col">
              <div class="form-group text-center py-0 mb-0">
                <span class="form-text text-muted m-0" style="font-size: 0.8rem">Для редактирования нужно отметить checkbox (Редактировать)</span>
              </div>
            </div>
          </div>
        </div>
        <div id="btn-<?= $row['clients_id'] ?>" style="display: none;">
          <div class="form-row pt-4 mx-4 mb-0 justify-content-between justify-content-sm-end">
            <button class="btn btn-outline-warning mr-2" name="updateClnt" id="<?= $row['clients_id'] ?>">изменить</button>
            <button class="btn btn-outline-danger ml-2" name="deleteClnt" id="<?= $row['clients_id'] ?>">удалить</button>
          </div>
        </div>
      </div>

    </div>

<?php
  }
} else {
  echo "ошибка запроса к бд";
}
?>