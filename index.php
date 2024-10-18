<?php
require_once "service/database.php";
session_start();

if ($_SESSION['is_login'] == false) {
    header("location : login.php");
}

define("APP_NAME", "PENDEKAR BAR & RESTO");
//require_once (membutuhkan satu kali koneksi to database) {function} : force project jadi error tidak lanjut ke project berikutnya//
//define (final value website) : parameter 1 nama, parameter 2 value//

$select_meja_query = "SELECT * FROM meja";
$count_meja_query = "SELECT COUNT(status) as total_count, SUM(status=1) as total_row FROM meja";

$select_meja = $db->query($select_meja_query);
$count_meja = $db->query($count_meja_query);

$status = $count_meja->fetch_assoc();
$jumlah_meja = $status['total_count'];
$meja_isi = $status['total_row'];

$is_full = false;


if ($jumlah_meja == $meja_isi) {
    $is_full = true;
}


//pemanggilan meja = query untuk mengammbil semua data meja
//eksekusi
$db->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css" />
    <title> <?= APP_NAME ?> </title>
</head>

<body id="meja">

    <?php include("layout/header.php") ?>

    <div class="gambar">
        <img src="./ui/8.jpg" style="width:505px;" align="left">
        <img src="./ui/10.jpg" style="width:505px;" align="left">
        <img src="./ui/9.jpg" style="width:508px;" align="super-left-grid">
    </div>
    </br>
    </br>
    <h1 align="center">
        <?php
        $sisa_meja =  $jumlah_meja - $meja_isi;

        if ($is_full) {
            echo "<h1 align='center'>Meja Penuh</h1>";
        } else {
            echo "<h1 align='center'>$sisa_meja Meja Kosong</h1>";
        }
        ?>
    </h1>

    <div class="container">
        <?php
        foreach ($select_meja as $meja) {
            $warna_meja = "";
            if ($meja['tipe_meja'] == "Standard") {
                $warna_meja = "#aeaaaadb";
            }
            if ($meja['tipe_meja'] == "Premium") {
                $warna_meja = "#FFD700";
            }
            if ($meja['tipe_meja'] == "VVIP") {
                $warna_meja = "#FFFFE0";
            }
            if ($meja['tipe_meja'] == "Eksklusif") {
                $warna_meja = "#708090";
            }

            //maaping lewat foreach
        ?>
            <div class="card" style="background-color:<?= $warna_meja ?>" onclick="goToMeja('<?= $meja['no_meja'] ?>', '<?= $meja['nama_pelanggan'] ?>')">
                <b><?= $meja['tipe_meja'] . " " . $meja['no_meja'] ?></b>
                <p>
                    <?= $meja['nama_pelanggan'] == NULL && $meja['jumlah_orang'] ==  NULL ? "meja kosong"
                        : $meja['nama_pelanggan'] .  "<br>" . $meja['jumlah_orang'] . " orang" ?>
                </p>
            </div>

        <?php  } ?>
    </div>

    <script>
        function goToMeja(no_meja, nama_pelanggan) {
            const url = "meja.php";
            const params = `?no_meja=${no_meja}&nama_pelanggan=${nama_pelanggan}`

            window.location.replace(url + params);
        }
    </script>
</body>

</html>