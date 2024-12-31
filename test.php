<?php
require_once 'config.php';
// initiate
$pdf = new \TonchikTm\PdfToHtml\Pdf('test.pdf', array(
    'pdftohtml_path' => '/usr/bin/pdftohtml',
    'pdfinfo_path' => '/usr/bin/pdfinfo'
));

// example for windows
// $pdf = new \TonchikTm\PdfToHtml\Pdf('test.pdf', [
//     'pdftohtml_path' => '/path/to/poppler/bin/pdftohtml.exe',
//     'pdfinfo_path' => '/path/to/poppler/bin/pdfinfo.exe'
// ]);

// get pdf info
$pdfInfo = $pdf->getInfo();

// get count pages
$countPages = $pdf->countPages();

// get content from one page
$contentFirstPage = $pdf->getHtml()->getPage(1);

// get content from all pages and loop for they
foreach ($pdf->getHtml()->getAllPages() as $page) {
    echo $page . '<br/>';
}