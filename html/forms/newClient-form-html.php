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
  <div class="find-form-wrapper bg-light mt-2 mb-3 no-m-p">
    <h3 class="text-center mt-2 mb-3">Добавить нового клиента</h3>
    <form method="post" action="" role="form">
      <div class="form-group pl-2 pr-2">
        <input class="form-control " type="text" name="clnt_name" autocomplete="off" placeholder="Введите название клиента" autofocus>
        <hr>
      </div>
      <div class="form-group pl-2 pr-2">
        <input id="btn" class="form-control btn btn-success text-custom btn-block btn-sm" type="submit" value="Добавить клиента" name="new_clnt">
        <hr>
      </div>
    </form>
    <?php if ($role >= 9) { ?>
      <form method="get" action="../html/">
        <div class="form-group pl-2 pr-2">
          <input type="hidden" name="newClient" value="updateClient" />
          <input id="btn" class="form-control btn btn-danger text-custom btn-block btn-sm" type="submit" value="Редакторовать записи БД">
        </div>
      </form>
    <?php } ?>
  </div>
</div>
<div class="row justify-content-center">
  <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["new_clnt"])) {
      unset($_POST["new_clnt"]);
      require_once "../php/new-client.php";
    }
  ?>
</div>
