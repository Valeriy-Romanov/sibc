<!doctype html>
<html lang="ru" class="h-100">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="none" />
	<link href="https://fonts.googleapis.com/css?family=Asap&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
	<title>
		<?php
		switch ($_SERVER['QUERY_STRING']) {
			case 'mainPage':
				$title = 'Главная || База данных "учет талонов"';
				break;
			case 'findTalons':
				$title = 'Поиск талона(ов) || База данных "учет талонов"';
				break;
			case 'getTalons':
				$title = 'Выдать талон(ы) || База данных "учет талонов"';
				break;
			case 'returnTalons':
				$title = 'Погасить талон(ы) || База данных "учет талонов"';
				break;
			case 'newClient':
				$title = 'Добавить клиента || База данных "учет талонов"';
				break;
			case 'newUser':
				$title = 'Добавить пользователя || База данных "учет талонов"';
				break;
			case 'report=users':
				$title = 'Отчет по сотрудникам || База данных "учет талонов"';
				break;
			case 'report=clients':
				$title = 'Отчет по клиентам || База данных "учет талонов"';
				break;
			case 'report=common':
				$title = 'Отчет общий || База данных "учет талонов"';
				break;
			default:
				$title = 'Главная || База данных "учет талонов"';
				break;
		}
		print_r($title);
		?>
	</title>>
  <!-- Bootstrap core CSS -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="../css/thems-css/style-custom.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/datepicker.css">
</head>

<body class="d-flex flex-column body-bg-custom h-100">
  <noscript>JavaScript must be enabled </noscript>
