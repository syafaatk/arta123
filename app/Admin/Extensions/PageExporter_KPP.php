<?php

namespace App\Admin\Extensions;

use OpenAdmin\Admin\Grid\Exporters\ExcelExporter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Mpdf\Mpdf;

class PageExporter_KPP extends ExcelExporter
{
    protected $fileName = 'KPP.xlsx';

    protected $columns = [
        'id' => 'ID',
        'name_kpp' => 'Nama KPP',
    ];

    public function export()
    {
        $filename = $this->getFilename();

        $this->grid->setGridExportName($filename);

        // Create a new Mpdf instance
        $mpdf = new Mpdf();

        // Start buffering PDF content
        ob_start();

        // Begin PDF document
        $mpdf->WriteHTML('<html><body>');

        // Add a title
        $mpdf->WriteHTML('<h1>Invoice</h1>');

        // Add table header
        $mpdf->WriteHTML('<table border="1" cellpadding="5" cellspacing="0">');
        $mpdf->WriteHTML('<thead><tr>');
        foreach ($this->columns as $label) {
            $mpdf->WriteHTML('<th>' . $label . '</th>');
        }
        $mpdf->WriteHTML('</tr></thead>');

        // Add table data
        $mpdf->WriteHTML('<tbody>');
        foreach ($this->getData() as $record) {
            $mpdf->WriteHTML('<tr>');
            foreach ($this->columns as $column => $label) {
                $mpdf->WriteHTML('<td>' . data_get($record, $column) . '</td>');
            }
            $mpdf->WriteHTML('</tr>');
        }
        $mpdf->WriteHTML('</tbody></table>');

        // Add a signature line
        $mpdf->WriteHTML('<p>______________________________</p>');

        // End PDF document
        $mpdf->WriteHTML('</body></html>');

        // End buffering and get PDF content
        $pdfContent = ob_get_clean();

        // Set PDF content for download
        $mpdf->Output($filename, 'D');
        exit;
    }

    // Add the getFilename method
    public function getFilename()
    {
        return $this->fileName;
    }
}