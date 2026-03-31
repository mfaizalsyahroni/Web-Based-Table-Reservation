<?php
require_once("service/pdf/fpdf.php");
require_once("service/database.php");

session_start();

setlocale(LC_TIME, 'id_ID.UTF-8');

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

if (isset($_POST['report'])) {
    $hari = $_POST['hari'];
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetTitle("Laporan Pengunjung");
    $pdf->SetFont("Arial", "B", 14);


    $select_history_querry = "SELECT * FROM history WHERE hari='$hari'";
    $select_history = $db->query($select_history_querry);


    if ($select_history->num_rows > 0) {
        $pdf->Text(10, 8, "Laporan Pengunjung Pendekar Bar & Resto Tanggal " . date('d F Y', strtotime($hari)));
        $pdf->Ln(10);
        $pdf->Text(10, 14, "Total $select_history->num_rows pengunjung.");
        $pdf->Cell(25, 10, "No. Meja", 1, 0);
        $pdf->Cell(25, 10, "Customer", 1, 0);
        $pdf->Cell(40, 10, "Pesanan", 1, 0);
        $pdf->Cell(30, 10, "Total Bayar", 1, 0);
        $pdf->Cell(30, 10, "Tanggal", 1, 0);
        $pdf->Cell(30, 10, "Jam Order", 1, 0);
        $pdf->Cell(40, 10, "", 0, 1);
        foreach ($select_history as $history) {
            $no_meja = $history["no_meja"];
            $nama_pelanggan = $history["nama_pelanggan"];
            $order_details = str_replace(',', "\n", $history["order_details"]); // Ubah koma jadi newline
            $total_bayar = "Rp. " . number_format($history["total_bayar"], 0, ',', '.');
            $hari = date('d-m-Y', strtotime($history["hari"]));
            $jam = date('H:i', strtotime($history["jam"]));

            // Hitung tinggi berdasarkan jumlah baris di order_details
            $lineHeight = 5; // Tinggi per baris
            $colWidth = 40; // Lebar kolom order_details
            $lines = substr_count($order_details, "\n") + 1; // Hitung jumlah baris berdasarkan "\n"
            $rowHeight = max(10, $lines * $lineHeight); // Minimal 10, atau sesuai teks

            // Simpan posisi awal
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            // Cetak kolom tetap sebelum order_details
            $pdf->Cell(25, $rowHeight, $no_meja, 1, 0);
            $pdf->Cell(25, $rowHeight, $nama_pelanggan, 1, 0);

            // Cetak order_details dengan MultiCell agar turun ke bawah dalam kolomnya sendiri
            $pdf->SetFont("Arial", "", 9);
            $pdf->MultiCell($colWidth, $lineHeight, $order_details, 1);
            $pdf->SetFont("Arial", "B", 14);

            // Pindahkan posisi X setelah MultiCell agar sel berikutnya tetap sejajar
            $pdf->SetXY($x + 25 + 25 + $colWidth, $y);

            // Cetak kolom lainnya dengan tinggi yang sama agar tabel tetap rapi
            $pdf->Cell(30, $rowHeight, $total_bayar, 1, 0, 'R');
            $pdf->Cell(30, $rowHeight, $hari, 1, 0);
            $pdf->Cell(30, $rowHeight, $jam, 1, 1);
        }



        // }
        $pdf->Output();
    } else {
        $pdf->SetFont("Arial", "B", 14);
        $pdf->Cell(38, 10, "Tidak ada laporan untuk tanggal $hari", 0, 1);
        $pdf->Output();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css " />

    <title>REPORT</title>
</head>

<body id="report">
    <?php include("layout/header.php") ?>
    <div class="super-center">

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <h3>
                <center>Cetak PDF</center>
            </h3>
            <input type="date" name="hari"></input>
            <br>
            <button type="submit" name="report">Generate Report</button>
        </form>
    </div>
    <div class="signature">
        <p>
            <center>PIC</center>
        </p>
        <p>(Muhammad Faizal)</p>
    </div>
</body>

</html>