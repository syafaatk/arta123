<?php 
echo "<table border='1' class='table grid-table table-sm table-hover select-table'>";
echo "
<tr>
    <th style='border: 1px solid rgb(62, 57, 57);'>ID</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Keterangan</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Clint_ID</th>
    <th style='border: 1px solid rgb(62, 57, 57);'>Tahun</th>
</tr>";

for ($i = 0; $i < 1; $i++) {
    // Memeriksa apakah nilai $status[$i] tidak null
    if (isset($status[$i])) {
        $statusArray = json_decode($status[$i], true);

        echo "<tr>";
        // Mengonversi nilai menjadi angka dan kemudian memformatnya
        $id = $statusArray['id'];
        $keterangan = $statusArray['keterangan'];
        $nama_wp = $statusArray['nama_wp'];
        $tahun = $statusArray['tahun'];

        // Menambahkan nilai item ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $id . "</td>";

        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $keterangan . "</td>";
        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $nama_wp . "</td>";
        // Menambahkan nilai quantity ke dalam tabel
        echo "<td style='border: 1px solid rgb(62, 57, 57);'>" . $tahun . "</td>";

        echo "</tr>";
    } else {
        // Jika nilai $status[$i] null, cetak baris kosong
        echo "<tr><td colspan='5' style='border: 1px solid rgb(62, 57, 57);'>No data available</td></tr>";
    }
}

echo "</table>";
?>