<?php
require_once("service/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location : login.php");
}

define("APP_NAME", "NOMOR MEJA ");

$no_meja = "";
$nama_pelanggan = "";
$update_notification = "";

if (isset($_GET['no_meja']) && $_GET['no_meja'] !== "") {
    $no_meja = $_GET['no_meja'];
}

if (isset($_GET['nama_pelanggan']) && $_GET['nama_pelanggan'] !== "") {
    $nama_pelanggan = $_GET['nama_pelanggan'];
    header("location: finish_checkout.php?no_meja=$no_meja&nama_pelanggan=$nama_pelanggan");
}

if (isset($_POST['UPDATE'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jumlah_orang = $_POST['jumlah_orang'];

    $update_meja_query = "UPDATE meja SET nama_pelanggan='$nama_pelanggan', jumlah_orang='$jumlah_orang', status=1 WHERE no_meja='$no_meja'";

    $update_meja = $db->query($update_meja_query);

    if ($update_meja) {
        header("location: index.php");
    } else {
        $update_notification = "Gagal update data meja, silahkan coba lagi";
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css" />
    <title>Update Meja</title>
</head>

<body id="nomeja">
    <?php include("layout/header.php"); ?>
    <div class="super-center">
        
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <h1><?= APP_NAME;
            echo $no_meja ?></h1>
        <i><?= $update_notification ?></i>
            <label>Nama Pelanggan</label>
            <input name="nama_pelanggan" />
            <label>Jumlah Orang</label>
            <input name="jumlah_orang" />
            <br>
            <button type="submit" name="UPDATE">UPDATE MEJA</button>
        </form>
    </div>
    <div class="signature">
        <p>
            <center>PIC</center>
        </p>
        <p>
            (Muhammad Faizal)
        </p>
        <br>
    </div>
</body>

</html>