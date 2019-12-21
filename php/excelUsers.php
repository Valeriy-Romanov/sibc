<?php

session_start();
require_once '../php/connect_db.php';
require_once '../php/function.php';
require_once '../PHPExcel/Classes/PHPExcel.php';
require_once '../PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

///////////////////////проверка сессии и прав доступа///////////////////////////////////

$user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
$fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
$role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
if (empty($user_id) && empty($fname) && empty($role)) {
    logout();
}
if ($role < 5) {
    logout();
}
///////////////////////////////////////////////////////////////////////////////////////////

ob_end_clean();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

// Create new PHPExcel object
$newFile = new PHPExcel();

$newFile->getActiveSheet()->setTitle(''.$_SESSION['user_name'].'');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$newFile->setActiveSheetIndex(0);

$newFile->getActiveSheet()->mergeCells("A1:I1");
$newFile->getActiveSheet()->setCellValue("A1", "A1:I1");

// Add some data
$newFile->setActiveSheetIndex(0)
			->setCellValue('A1', 'Сотрудником '.$_SESSION['fname'].' за период с '.$_SESSION['startDate'].' по '.$_SESSION['finishDate'].' выдано '.$_SESSION['number_1'].' шт талонов, из них:')
            ->setCellValue('A2', '')
            ->setCellValue('B2', 'ДТ-10л')
			->setCellValue('C2', 'Аи92-10л')
			->setCellValue('D2', 'ГАЗ-10л')
			->setCellValue('E2', 'Аи95-10л')
			->setCellValue('F2', 'ДТ-20л')
			->setCellValue('G2', 'Аи92-20')
			->setCellValue('H2', 'ГАЗ-20л')
			->setCellValue('I2', 'Аи95-20л')
			->setCellValue('A3', 'В штуках')
            ->setCellValue('B3', ''.$_SESSION['dt10_1'].'')
			->setCellValue('C3', ''.$_SESSION['a9210_1'].'')
			->setCellValue('D3', ''.$_SESSION['gaz10_1'].'')
			->setCellValue('E3', ''.$_SESSION['a9510_1'].'')
			->setCellValue('F3', ''.$_SESSION['dt20_1'].'')
			->setCellValue('G3', ''.$_SESSION['a9220_1'].'')
			->setCellValue('H3', ''.$_SESSION['gaz20_1'].'')
			->setCellValue('I3', ''.$_SESSION['a9520_1'].'');





$newFile->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);


$newFile->getActiveSheet()->getStyle("A1:I2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$border = array(
	'borders'=>array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '000000')
		)
	)
);
 
$newFile->getActiveSheet()->getStyle("A1:I3")->applyFromArray($border);

// Rename worksheet


$nameFile = date("Y-m-d_H-i-s");
// header("location: ../html/");
// // Redirect output to a client’s web browser (Excel2007)
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="'.$nameFile.'.xlsx"');
// header ('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1

// // If you're serving to IE over SSL, then the following may be needed
// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

// header ('Pragma:no-cache'); // HTTP/1.0

$newFile = PHPExcel_IOFactory::createWriter($newFile, 'Excel2007');
$file = "../files/$nameFile.xlsx";
$newFile->save($file);

echo "<a href=\"$file\" download>Скачать файл</a>";
?>
