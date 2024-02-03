<?php 
echo "<table border='1'>";
echo "
<tr>
    <th style='border: 1px solid black;'>Item Pemeriksaan</th>
    <th style='border: 1px solid black;'>Quantity</th>
    <th style='border: 1px solid black;'>DPP Faktur Pajak</th>
    <th style='border: 1px solid black;'>DPP Gunggung</th>
    <th style='border: 1px solid black;'>PPN PPH</th>
</tr>";

for ($i = 0; $i < 100; $i += 33) {
    // Memeriksa apakah nilai $status[$i] tidak null
    if (isset($status[$i])) {
        $statusArray = json_decode($status[$i], true);

        echo "<tr>";
        // Menambahkan nilai item_pemeriksaan ke dalam tabel
        echo "<td style='border: 1px solid black;'>" . $statusArray['item_pemeriksaan'] . "</td>";

        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid black;'>" . $statusArray['quantity'] . "</td>";

        // Menambahkan nilai dpp_faktur_pajak ke dalam tabel
        echo "<td style='border: 1px solid black;'>" . $statusArray['dpp_faktur_pajak'] . "</td>";

        // Menambahkan nilai dpp_gunggung ke dalam tabel
        echo "<td style='border: 1px solid black;'>" . $statusArray['dpp_gunggung'] . "</td>";

        // Menambahkan nilai ppn_pph ke dalam tabel
        echo "<td style='border: 1px solid black;'>" . $statusArray['ppn_pph'] . "</td>";

        echo "</tr>";
    } else {
        // Jika nilai $status[$i] null, cetak baris kosong
        echo "<tr><td colspan='5' style='border: 1px solid black;'>No data available</td></tr>";
    }
}

echo "</table>";
?>