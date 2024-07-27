
<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// HTML content with inline CSS for styling the receipt
$htmlContent = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .receipt {
            width: 80mm; /* Typical receipt width */
            margin: 0 auto;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .items {
            margin-top: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">Store Name</div>
        <div class="header">Address Line 1</div>
        <div class="header">Address Line 2</div>
        <div class="header">Phone: (123) 456-7890</div>
        <div class="header">Date: ' . date('Y-m-d H:i:s') . '</div>
        <div class="items">
            <div class="item">
                <span>Item 1</span>
                <span>$10.00</span>
            </div>
            <div class="item">
                <span>Item 2</span>
                <span>$5.00</span>
            </div>
        </div>
        <div class="total">
            Total: $15.00
        </div>
    </div>
</body>
</html>
';

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($htmlContent);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF to a file
$pdfOutput = $dompdf->output();
$pdfFile = tempnam(sys_get_temp_dir(), 'print') . '.pdf';
file_put_contents($pdfFile, $pdfOutput);

// Print the PDF file using lp (Unix-like systems) or print (Windows)
$printerName = 'NOMBRE_DE_IMPRESORA'; // Replace with your actual printer name

// Unix-like systems
$command = "lp -d $printerName $pdfFile";

// Windows
// $command = "print /D:$printerName $pdfFile";

exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Impresion exitosa";
} else {
    echo "Error haciendo impresion";
}

// Clean up the temporary files
unlink($pdfFile);
?>
