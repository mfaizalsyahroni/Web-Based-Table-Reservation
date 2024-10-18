<?php
require_once("service/pdf/fpdf.php");
require_once("service/database.php");

session_start();

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
        $pdf->Text(10, 6, "Laporan Pengunjung Pendekar Bar & Resto Tanggal $hari");
        $pdf->Ln(6);
        $pdf->Text(10, 12, "Total $select_history->num_rows pengunjung.");
        $pdf->Cell(24, 10, "No_meja", 1, 0);
        $pdf->Cell(48, 10, "Nama_pelanggan", 1, 0);
        $pdf->Cell(38, 10, "Hari", 1, 0);
        $pdf->Cell(38, 10, "Jam keluar", 1, 0);
        $pdf->Cell(40, 10, "", 0, 1);
        foreach ($select_history as $history) {
            $pdf->Cell(24,  10, $history["no_meja"], 1, 0);
            $pdf->Cell(48, 10, $history["nama_pelanggan"], 1, 0);
            $pdf->Cell(38, 10, $history["hari"], 1, 0);
            $pdf->Cell(38, 10, $history["jam"], 1, 1);
        }
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
    <link rel="stylesheet" href="style1.css " />
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
        <br>
    </div>
</body>

</html>