<?php

//проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) || empty($fname) || empty($role)) {
  logout();
}

if ($_SESSION['role'] < 5) {
  echo '<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
              <a class="navbar-brand navbar-brand-custom" href="/html?mainPage">Силай-Инвест</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="nav navbar-nav mr-auto">
                <li class="nav-item">
                  <a class="nav-link nav-link-custom" href="/html?mainPage">Главная</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-custom" href="/html?findTalons">Найти талон</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-custom" href="/html?returnTalons">Погасить талон</a>
                </li>
              </ul>
              <span class="text-warning mr-3 nav-text-custom">Вы вошли как: ' . $fname . '</span>
              <button class="btn btn-outline-danger my-2 my-sm-0 btn-out" type="submit" onClick="document.location = \'?out\'">Выход</button>
            </div>
            </nav>';
}
if ($_SESSION['role'] >= 5) {
  echo '<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
            <a class="navbar-brand navbar-brand-custom" href="/html?mainPage">Силай-Инвест</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="nav navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?mainPage">Главная</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?findTalons">Найти талон</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?getTalons">Выдать талон</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?returnTalons">Погасить талон</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?newClient">Добавить клиента</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-link-custom" href="#" id="report-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Отчеты
          </a>
          <div class="dropdown-menu bg-dark dropdown-menu-custom" aria-labelledby="report-menu">
            <a class="dropdown-item dropdown-item-custom" href="/html?report=users">По сотрудникам</a>
            <a class="dropdown-item dropdown-item-custom" href="/html?report=clients">По клиентам</a>
            <a class="dropdown-item dropdown-item-custom" href="/html?report=common">Общий</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="/html?newUser">Добавить пользователя</a>
        </li>
      </ul>
      <span class="text-warning mr-3 nav-text-custom">Вы вошли как: ' . $fname . '</span>
      <button class="btn btn-outline-danger my-2 my-sm-0 btn-out" type="submit" onClick="document.location = \'?out\'">Выход</button>
      </div>
    </nav>';
}
