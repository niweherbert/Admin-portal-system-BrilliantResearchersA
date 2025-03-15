<?php
require 'vendor/autoload.php'; // Install dompdf using Composer: composer require dompdf/dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

$table_data = json_decode($_POST['table_data'], true);

$html = '
<img src="SHDR.png.png" alt="Company Logo" style="width: 100px; height: auto;">
<h2>Materials Request</h2>
<p><strong>Project Name:</strong> ' . htmlspecialchars($_POST["project_name"]) . '</p>
<p><strong>Location:</strong> ' . htmlspecialchars($_POST["location"]) . '</p>
<p><strong>Requisition Number:</strong> ' . htmlspecialchars($_POST["req_number"]) . '</p>
<p><strong>Date:</strong> ' . htmlspecialchars($_POST["date"]) . '</p>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Description</th>
            <th>Material Name</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>';

foreach ($table_data as $row) {
    if (array_filter($row)) { // Skip empty rows
        $html .= '
        <tr>
            <td>' . htmlspecialchars($row[0]) . '</td>
            <td>' . htmlspecialchars($row[1]) . '</td>
            <td>' . htmlspecialchars($row[2]) . '</td>
            <td>' . htmlspecialchars($row[3]) . '</td>
            <td>' . htmlspecialchars($row[4]) . '</td>
            <td>' . htmlspecialchars($row[5]) . '</td>
        </tr>';
    }
}

$html .= '
    </tbody>
</table>
<p>Prepared by: Eng. Phenias IMANANIGITANGAZA</p>
<p>Approved by CEO of SHDR Ltd: Eng. IGIRANEZA Meschack</p>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("materials_request.pdf", ["Attachment" => 1]); // 1 for download, 0 for inline view
?>