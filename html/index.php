<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once '../PHPExcel/Classes/PHPExcel.php';
require_once '../php/connect_db.php';
require_once '../php/function.php';

//проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
  logout();
}
?>
<!-- Begin header -->
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
      <?php
      if (isset($_GET["mainPage"]) || !$_GET) {
        if ($role < 5) {
          getReturn($fname, $link);
        } else {
          getGiveReturn($fname, $link);
        }
      }
      ?>
    </div>
    <?php
    if (isset($_GET["findTalons"])) {
      if ($role < 5) {
        require_once "../html/forms/find-form-oprt_html.php";
      } else {
        require_once "../html/forms/find-form-all_html.php";
      }
    }
    if (isset($_GET["getTalons"])) {
      require_once "../html/forms/give-talon_form.php";
    }
    if (isset($_GET["returnTalons"])) {
      require_once "../html/forms/return-talon_form.php";
    }
    if (isset($_GET["newClient"])) {
      if ($role >= 9 && $_GET["newClient"] == "updateClient") {
        require_once "../php/updateClient.php";
      } else {
        require_once "../html/forms/newClient-form-html.php";
      }
    }
    if (isset($_GET["out"])) {
      unset($_GET["out"]);
      logout();
    }
    ?>
  </div>
</main>
<!--=================================================-->
<!-- Footer start -->

<?php
require_once "../html/templates/footer.php";
?>