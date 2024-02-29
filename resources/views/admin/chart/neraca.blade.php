<?php 
echo "<table border='1' class='table grid-table table-sm table-hover select-table'>";
echo "
<tr>
    <th style='border: 1px solid rgb(62, 57, 57);'>Item</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Total</th>
</tr>";

for ($i = 0; $i < 220; $i += 33) {
    // Memeriksa apakah nilai $status[$i] tidak null
    if (isset($status[$i])) {
        $statusArray = json_decode($status[$i], true);

        echo "<tr>";
        // Mengonversi nilai menjadi angka dan kemudian memformatnya
        $item = $statusArray['item_name'];
        $total = number_format(floatval($statusArray['total']), 0, ',', '.');

        // Menambahkan nilai item ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $item . "</td>";

        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $total . "</td>";

        echo "</tr>";
    } else {
        // Jika nilai $status[$i] null, cetak baris kosong
        echo "<tr><td colspan='5' style='border: 1px solid rgb(62, 57, 57);'>No data available</td></tr>";
    }
}

echo "</table>";
?>