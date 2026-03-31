<?php
require_once "service/database.php";
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] == false) {
    header("Location: login.php");
    exit;
}

define("APP_NAME", "PENDEKAR BAR & RESTO");
setlocale(LC_TIME, 'id_ID.UTF-8');
date_default_timezone_set('Asia/Jakarta');

$select_meja_query = "SELECT * FROM meja";
$count_meja_query = "SELECT COUNT(status) as total_count, SUM(status=1) as total_row FROM meja";

$select_meja = $db->query($select_meja_query);
$count_meja = $db->query($count_meja_query);

$status = $count_meja->fetch_assoc();
$jumlah_meja = $status['total_count'];
$meja_isi = $status['total_row'];

$is_full = ($jumlah_meja == $meja_isi);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        .card {
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            width: 180px;
            height: 120px;
            margin: 2px;
            text-align: center;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .min-order-text {
            font-size: 12px;
            color: red;
        }

        .kouta-table-check {
            font-size: 12px;
            color: blue;
        }
    </style>
</head>

<body id="meja">

    <?php include("layout/header.php"); ?>

    <div class="gambar">
        <img src="./ui/header.jpg" style="width: 100%; height: 300px;">
    </div><br><br>

    <h1>
        <?= $is_full ? "Meja Penuh" : ($jumlah_meja - $meja_isi) . " Meja Kosong" ?>
    </h1>

    <div class="container">
        <?php foreach ($select_meja as $meja): ?>
            <?php
            $warna_meja = "#aeaaaadb"; // Default Standard
            $min_order_text = "";
            $kouta_table_check = "";

            if ($meja['tipe_meja'] == "Premium") {
                $warna_meja = "#FFD700";
            } elseif ($meja['tipe_meja'] == "VVIP") {
                $warna_meja = "#FFFFE0";
                $min_order_text = "Min. Order Rp. 1.000.000";
                $kouta_table_check = "kouta 10 orang";
            } elseif ($meja['tipe_meja'] == "Eksklusif") {
                $warna_meja = "#708090";
                $min_order_text = "Min. Order Rp. 500.000";
                $kouta_table_check = "kouta 8 orang";
            }

            $is_occupied = !empty($meja['nama_pelanggan']);
            $target_page = $is_occupied ? "finish_checkout.php" : "meja.php";
            $link = "$target_page?no_meja=" . urlencode($meja['no_meja']) . "&nama_pelanggan=" . urlencode($meja['nama_pelanggan']);
            ?>
            <a href="<?= $link ?>" style="text-decoration: none; color: inherit;">
                <div class="card" style="background-color: <?= $warna_meja ?>;">
                    <b><?= $meja['tipe_meja'] . " " . $meja['no_meja'] ?></b>
                    <p>
                        <?php
                        if (empty($meja['nama_pelanggan']) && empty($meja['jumlah_orang'])) {
                            echo "Meja Kosong";
                        } else {
                            echo $meja['nama_pelanggan'] . "<br>" . $meja['jumlah_orang'] . " orang";
                        }
                        ?>
                    </p>
                    <?php if (empty($meja['nama_pelanggan']) && !empty($min_order_text) && !empty($kouta_table_check)): ?>
                        <p class="min-order-text"><?= $min_order_text ?></p>
                        </br>
                        <p class="kouta-table-check"><?= $kouta_table_check ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</body>

</html>