<?php 
echo "<table border='1' class='table grid-table table-sm table-hover select-table'>";
echo "
<tr>
    <th style='border: 1px solid rgb(62, 57, 57);'>No</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>ID ET</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Item ET</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>ID LR</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Item LR</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Quantity</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>DPP Faktur Pajak</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>DPP Gunggung</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>PPN PPH</th>
</tr>";
$no=1;
for ($i = 0; $i < 245; $i += 35) {
    // Memeriksa apakah nilai $status[$i] tidak null
    if (isset($status[$i])) {
        $statusArray = json_decode($status[$i], true);

        echo "<tr>";
        // Mengonversi nilai menjadi angka dan kemudian memformatnya
        $itemet = $statusArray['item_pemeriksaan_id'];
        $itemlr = $statusArray['item_laru'];
        $item_pemeriksaan = $statusArray['item_pemeriksaan'];
        $item_name = $statusArray['item_name'];
        $quantity = number_format(floatval($statusArray['quantity']), 0, ',', '.');
        $dpp_faktur_pajak = number_format(floatval($statusArray['dpp_faktur_pajak']), 0, ',', '.');
        $dpp_gunggung = number_format(floatval($statusArray['dpp_gunggung']), 0, ',', '.');
        $ppn_pph = number_format(floatval($statusArray['ppn_pph']), 0, ',', '.');

        // Menambahkan nilai item_pemeriksaan ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>".$no++."</td>";
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>".$itemet."</td>";
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $item_pemeriksaan . "</td>";
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>".$itemlr."</td>";
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $item_name . "</td>";

        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $quantity . "</td>";

        // Menambahkan nilai dpp_faktur_pajak ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $dpp_faktur_pajak . "</td>";

        // Menambahkan nilai dpp_gunggung ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $dpp_gunggung . "</td>";

        // Menambahkan nilai ppn_pph ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $ppn_pph . "</td>";
        echo "</tr>";
    } else {
        // Jika nilai $status[$i] null, cetak baris kosong
        echo "<tr><td colspan='5' style='border: 1px solid rgb(62, 57, 57);'>No data available</td></tr>";
    }
}

echo "</table>";
?>