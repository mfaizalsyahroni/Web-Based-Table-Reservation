<?php
require_once("service/database.php");

session_start();

setlocale(LC_TIME, 'id_ID.UTF-8');

date_default_timezone_set('Asia/Jakarta');

$no_meja = "";
$nama_pelanggan = "";
$total_bayar = "";
$order_details = "";
$hari = "";
$jam = "";
$error_message = "";
$show_add_order_button = false;


if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

if (isset($_GET['no_meja']) && $_GET['no_meja']) {
    $no_meja = $_GET['no_meja'];
}

if (isset($_GET['nama_pelanggan']) && $_GET['nama_pelanggan']) {
    $nama_pelanggan = $_GET['nama_pelanggan'];
}


if (isset($_POST['payment'])) {
    $no_meja = $_POST['no_meja'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $order_details = $_POST['order_details'];
    $total_bayar = $_POST['total_bayar'];
    $hari = date('Y-m-d');
    $jam = date('H:i');

    // Ambil tipe_meja dari no_meja
    $check_meja_query = "SELECT tipe_meja FROM meja WHERE no_meja = '$no_meja'";
    $result = $db->query($check_meja_query);
    $meja_data = $result->fetch_assoc();
    $tipe_meja = $meja_data['tipe_meja'];

    // Validasi minimal bayar
    if ($tipe_meja == "Eksklusif" && $total_bayar < 500000) {
        $kurang = number_format(500000 - $total_bayar, 0, ',', '.');
        $error_message = "Pembayaran untuk meja <b>Eksklusif</b> minimal <b>Rp 500.000</b>. Anda masih kurang <b> Rp$kurang</b>.";
        $show_add_order_button = true;
    }

    if ($tipe_meja == "VVIP" && $total_bayar < 1000000) {
        $kurang = number_format(1000000 - $total_bayar, 0, ',', '.');
        $error_message = "Pembayaran untuk meja <b>VVIP</b> minimal <b>Rp1.000.000</b>. Anda masih kurang <b> Rp$kurang</b>.";
        $show_add_order_button = true;
    }

    if (!empty($error_message)) {

    } else {

    $insert_history_query = "INSERT INTO history(no_meja, nama_pelanggan, order_details, total_bayar, hari, jam) VALUES ('$no_meja', '$nama_pelanggan', '$order_details', '$total_bayar', '$hari', '$jam')";
    $insert_history = $db->query($insert_history_query);
    echo "Query: $insert_history_query";
    // Simpan ke database
    if ($insert_history) {
        header("location: index.php");
    } else {
        echo "payment tidak tersave di history";
    }
    $db->close();
    }
}

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css" />
    <title>Pembayaran</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selectedItems = JSON.parse(localStorage.getItem("selectedItems")) || {};
            let orderList = document.getElementById("orderList");

            let totalHarga = 0;
            let hargaMenu = {
                "Nachos": 25000,
                "Eye Steak": 70000,
                "Teriyaki Ramen": 50000,
                "Fettucini Chili": 30000,
                "Signature Exotic": 50000,
                "Green Light": 100000,
                "Drunk Master": 150000,
                "Marie Softly": 70000,
                "Strawberry Quiri": 90000,
                "Hawai Colada": 100000
            };

            for (let item in selectedItems) {
                if (selectedItems[item] > 0) {
                    let itemPrice = hargaMenu[item] * selectedItems[item];
                    totalHarga += itemPrice;
                    orderList.innerHTML += `<li>${item} x${selectedItems[item]} - Rp${itemPrice.toLocaleString()}</li>`;
                }
            }

            document.getElementById("totalPrice").innerText = "Total Bayar: Rp" + totalHarga.toLocaleString();

            // Simpan total harga ke sessionStorage untuk dikirim ke server
            sessionStorage.setItem("totalHarga", totalHarga);
        });
    </script>
</head>

<body id="nomeja">

    <?php include("layout/header.php"); ?>
    <h2>PAYMENT</h2>

    <?php if (!empty($error_message)): ?>
        <div class="error-card" id="errorBox">
            <p><?= $error_message ?></p>
            <?php if ($show_add_order_button): ?>
                <button onclick="window.location.href='meja.php?no_meja=<?= urlencode($no_meja) ?>&nama_pelanggan=<?= urlencode($nama_pelanggan) ?>'" class="add-order-btn">
                    Tambah Pesanan
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="position">
        <form action="">
            <h3>No Meja <?= ' ' . $no_meja ?></h3>
            <h3>Order Atas Nama <?= $nama_pelanggan ?></h3>
            <ul id="orderList"></ul>
            <h3 id="totalPrice"></h3>
        </form>
    </div>

    <div class="pay">
        <form action="proses_payment.php" method="POST" onsubmit="setTotalHarga()">
            <input type="hidden" name="no_meja" value="<?= htmlspecialchars($no_meja) ?>">
            <input type="hidden" name="nama_pelanggan" value="<?= htmlspecialchars($nama_pelanggan) ?>">
            <input type="hidden" name="order_details" id="order_details">
            <input type="hidden" name="total_bayar" id="total_bayar">
            <h6>Silakan scan QR Code di bawah:</h6>
            <img class="myQR" src="./ui/qr.jpg" alt="QR Code">
            <button type="submit" name="payment">SUCCES</button>
        </form>
    </div>

    <script>
        function setTotalHarga() {
            document.getElementById("total_bayar").value = sessionStorage.getItem("totalHarga");

            // Ambil rincian pesanan dari localStorage
            let selectedItems = JSON.parse(localStorage.getItem("selectedItems")) || {};
            let orderDetails = "";
            for (let item in selectedItems) {
                if (selectedItems[item] > 0) {
                    orderDetails += `${item} x${selectedItems[item]}, `;
                }
            }
            document.getElementById("order_details").value = orderDetails.slice(0, -2); // Menghapus koma terakhir
        }

        // Ini akan dieksekusi setelah form disubmit dan halaman di-redirect
        // Pastikan ini dieksekusi setelah data berhasil dikirim ke server
        document.querySelector("form[action='proses_payment.php']").addEventListener("submit", function() {
            // Hapus localStorage setelah pembayaran sukses
            localStorage.clear(); // <-- TAMBAHKAN BARIS INI
            // Redirect ke index.php setelah 1 detik (memberi waktu untuk localStorage.clear())
            setTimeout(function() {
                window.location.href = "index.php";
            }, 1000);
        });

        function closeErrorBox() {
            const box = document.getElementById('errorBox');
            if (box) {
                box.style.display = 'none';
            }
        }

        // Optional: otomatis hilang dalam 12 detik
        setTimeout(closeErrorBox, 12000);
    </script>
</body>

</html>