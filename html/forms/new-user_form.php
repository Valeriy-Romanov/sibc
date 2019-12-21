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
} //если условие выполняеться - не пускает
?>

<form method="POST">
    <fieldset>
        <legend>
            <h2>Добавить нового пользователя</h2>
        </legend>
        <fieldset id="light">
            <span style="margin-right: 16px; font-size: 1.1em;">Введите логин:</span>
            <input style="margin-bottom: 20px; margin-right: 20px ; font-size: 1.1em;" required type="text" name="log_NU" autocomplete="off"/>
            <span style="margin-right: 59px; font-size: 1.1em;">Введите ФИО:</span>
            <input style="font-size: 1.1em;" required type="text" name="fio_NU" autocomplete="off"/><br>
            <span style="margin-right: 7px; font-size: 1.1em;">Введите пароль:</span>
            <input style="margin-right: 20px; margin-bottom: 35px; font-size: 1.1em;" required type="text" name="pass_NU" autocomplete="off"/>
            <span style="margin-right: 9px; font-size: 1.1em;">Выберите категорию:</span>
            <select style="font-size: 1.1em;" name="role_NU" required>
                <option disabled selected>Категория пользователя</option>
                <option value="10">Администратор</option>
                <option value="9">Директор</option>
                <option value="7">Бухгалтер</option>
                <option value="5">Менеджер</option>
                <option value="3">Оператор</option>
            </select><br>
        </fieldset>
        <p>
        <div class="forLink">
            <div><input type="submit" name='new_user' value="Добавить пользователя" id="btn">
            </form>
        </div>
            <div>
                <?php
                    if($role >= 9){
                        echo '<form name="updateUser" action="../html/">
                                <input type="hidden" name="newUser" value="updateUser" />
                                <button for="updateUser" type="submit" id="btn">Редактировать записи БД</button>
                              </form>';
                    }
                ?>
            </div>
        </div>
    </fieldset>
        </p>
    


<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['new_user'])) {
    if (isset($_POST['role_NU'])) {
        require_once "../php/new-user.php";
    } else {
        echo '<span class="alert">Выберите категорию пользователя!!!</span>';
    }
}
?>