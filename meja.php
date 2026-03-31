<?php
require_once("service/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
    exit();
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
}

if (isset($_POST['UPDATE'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jumlah_orang = $_POST['jumlah_orang'];

    $update_meja_query = "UPDATE meja SET nama_pelanggan='$nama_pelanggan', jumlah_orang='$jumlah_orang', status=1 WHERE no_meja='$no_meja'";

    if ($db->query($update_meja_query)) {
        header("location: proses_payment.php?no_meja=$no_meja&nama_pelanggan=$nama_pelanggan");
        exit();
    } else {
        $update_notification = "Gagal update data meja, silakan coba lagi.";
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reservation2.css" />
    <title>Update Meja</title>
</head>

<body id="nomeja">
    <?php include("layout/header_meja.php"); ?>

    <div class="cart-container" id="cart" style="display: none;">
        <h2>Cart 🛒</h2>
        <ul id="cartList"></ul>
        <h3 id="cartTotalPrice"></h3>
        <button onclick="closeCart()">OK</button>
    </div>

    <!-- np -->

    <div class="pos" id="orderSummary">
        <!-- Total pesanan dan rincian menu akan ditampilkan di sini -->
    </div>



    <div class="position">
        <div class="w3-content">
            <img class="mySlides" src="./ui/food1.PNG" alt="Nachos">
            <img class="mySlides" src="./ui/food2.PNG" alt="Eye Steak">
            <img class="mySlides" src="./ui/food3.PNG" alt="Teriyaki Ramen">
            <img class="mySlides" src="./ui/food4.PNG" alt="Fettucini Chili">
            <div class="idk">
                <b>FOOD</b>
            </div>
            <center>
                <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
            </center>
        </div>

        <div class="super-center">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <h1><?= APP_NAME;
                echo $no_meja ?></h1>
                <i><?= $update_notification ?></i>
                <label><b>Nama Pelanggan</b></label>
                <input name="nama_pelanggan" autocomplete="off" />
                <label><b>Jumlah Orang</b></label>
                <input name="jumlah_orang" autocomplete="off" />
                <br>
                <button type="submit" name="UPDATE" id="checkoutButton">CHECKOUT</button>
            </form>
        </div>

        <div class="w3-display">
            <img class="slidedrink" src="./ui/drink1.PNG" alt="Signature Exotic">
            <img class="slidedrink" src="./ui/drink2.PNG" alt="Green Light">
            <img class="slidedrink" src="./ui/drink3.PNG" alt="Drunk Master">
            <img class="slidedrink" src="./ui/drink4.PNG" alt="Marie Softly">
            <img class="slidedrink" src="./ui/drink5.PNG" alt="Strawberry Quiri">
            <img class="slidedrink" src="./ui/drink6.PNG" alt="Hawai Colada">
            <div class="idw">
                <b>DRINK</b>
            </div>
            <center>
                <button class="w3-button w3-black w3-display-left" onclick="plusDrinkDivs(-1)">&#10094;</button>
                <button class="w3-button w3-black w3-display-right" onclick="plusDrinkDivs(1)">&#10095;</button>
            </center>
        </div>
    </div>

    <div class="signature">
        <p>
            <center>PIC</center>
        </p>
        <p>(Muhammad Faizal)</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Slideshow FOOD
            var slideIndex = 1;
            showDivs(slideIndex);

            function plusDivs(n) {
                showDivs(slideIndex += n);
            }

            function showDivs(n) {
                var i;
                var x = document.getElementsByClassName("mySlides");
                if (n > x.length) {
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = x.length;
                }
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                x[slideIndex - 1].style.display = "block";
            }

            // Slideshow DRINK
            var drinkSlideIndex = 1;
            showDrinkDivs(drinkSlideIndex);

            function plusDrinkDivs(n) {
                showDrinkDivs(drinkSlideIndex += n);
            }

            function showDrinkDivs(n) {
                var i;
                var drinks = document.getElementsByClassName("slidedrink");
                if (n > drinks.length) {
                    drinkSlideIndex = 1;
                }
                if (n < 1) {
                    drinkSlideIndex = drinks.length;
                }
                for (i = 0; i < drinks.length; i++) {
                    drinks[i].style.display = "none";
                }
                drinks[drinkSlideIndex - 1].style.display = "block";
            }

            // Memastikan FOOD & DRINK memiliki tombol yang benar
            document.querySelector(".w3-content .w3-display-left").addEventListener("click", function () {
                plusDivs(-1);
            });

            document.querySelector(".w3-content .w3-display-right").addEventListener("click", function () {
                plusDivs(1);
            });

            document.querySelector(".w3-display .w3-display-left").addEventListener("click", function () {
                plusDrinkDivs(-1);
            });

            document.querySelector(".w3-display .w3-display-right").addEventListener("click", function () {
                plusDrinkDivs(1);
            });

        });


        function showCart() {
            let selectedItems = JSON.parse(localStorage.getItem("selectedItems")) || {};
            let cartList = document.getElementById("cartList");
            let cartTotalPrice = document.getElementById("cartTotalPrice");
            let totalHarga = 0;
            // Pastikan hargaMenu didefinisikan di sini atau di scope global
            const hargaMenu = {
                "Nachos": 25000, "Eye Steak": 70000, "Teriyaki Ramen": 50000, "Fettucini Chili": 30000,
                "Signature Exotic": 50000, "Green Light": 100000, "Drunk Master": 150000,
                "Marie Softly": 70000, "Strawberry Quiri": 90000, "Hawai Colada": 100000
            };
            cartList.innerHTML = ""; // Kosongkan daftar cart
            for (let item in selectedItems) {
                if (selectedItems[item] > 0) {
                    let itemPrice = hargaMenu[item] * selectedItems[item];
                    totalHarga += itemPrice;
                    cartList.innerHTML += `<li>${item} x${selectedItems[item]} - Rp${itemPrice.toLocaleString()}</li>`;
                }
            }
            cartTotalPrice.innerText = "Total: Rp" + totalHarga.toLocaleString();
            document.getElementById("cart").style.display = "block"; // Tampilkan div cart
            document.querySelector(".pos").style.display = "block"; // Tampilkan div .pos (total pesanan dan rincian menu)
        }
        function closeCart() {
            document.getElementById("cart").style.display = "none"; // Sembunyikan div cart
            document.querySelector(".pos").style.display = "none"; // Sembunyikan div .pos
        }

        document.addEventListener("DOMContentLoaded", function () {
            let selectedItems = {}; // Objek untuk menyimpan pilihan makanan/minuman

            function selectItem(imgElement, itemName) {
                if (!selectedItems[itemName]) {
                    selectedItems[itemName] = 0;
                }
                selectedItems[itemName]++;

                if (selectedItems[itemName] === 3) {
                    imgElement.style.border = "5px solid green"; // Indikasi bahwa item sudah dipilih
                    imgElement.style.opacity = "0.5"; // Efek transparan untuk item yang dipilih
                } else if (selectedItems[itemName] > 3) {
                    selectedItems[itemName] = 3; // Batas maksimal adalah 3
                }

                // Simpan ke localStorage agar data tetap ada meskipun halaman di-refresh
                localStorage.setItem("selectedItems", JSON.stringify(selectedItems));
            }



            // Tambahkan event listener pada setiap gambar makanan
            document.querySelectorAll(".mySlides").forEach(img => {
                img.addEventListener("click", function () {
                    let itemName = this.getAttribute("alt"); // Ambil nama makanan dari atribut alt
                    selectItem(this, itemName);
                });
            });

            // Tambahkan event listener pada setiap gambar minuman
            document.querySelectorAll(".slidedrink").forEach(img => {
                img.addEventListener("click", function () {
                    let itemName = this.getAttribute("alt"); // Ambil nama minuman dari atribut alt
                    selectItem(this, itemName);
                });
            });

            // Tombol untuk lanjut ke pembayaran
            document.getElementById("checkoutButton").addEventListener("click", function () {
                window.location.href = "proses_payment.php"; // Redirect ke halaman pembayaran
            });
        });
    </script>
</body>

</html>	