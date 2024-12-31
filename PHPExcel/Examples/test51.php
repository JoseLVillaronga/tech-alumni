<?php
require_once 'config.php';
$inputFileType = PHPExcel_IOFactory::identify("2.pdf");
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load("2.pdf");
$total_sheets=$objPHPExcel->getSheetCount(); 
$allSheetName=$objPHPExcel->getSheetNames(); 
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0); 
$highestRow = $objWorksheet->getHighestRow(); 
$highestColumn = $objWorksheet->getHighestColumn();  
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
for ($row = 1; $row <= $highestRow;++$row) 
{  
	for ($col = 0; $col <$highestColumnIndex;++$col)
    {  
    	$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
        $arraydata[$row-1][$col]=$value; 
    }  
}
echo "<pre>";
print_r($arraydata);
echo "</pre>";