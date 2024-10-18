<header>
    <div>
        <a href="index.php">PENDEKAR BAR & RESTO</a>
    </div>
    <div>
        <?php
        if (isset($_SESSION['is_login'])) {
            echo "<div class='right'>";
            echo "<a href='report.php'>REPORT</a>";
            echo "<a href='logout.php'>LOGOUT</a>";
            echo "</div>";
        } else {
            echo "<a href='login.php'>login</a>";
        }
        ?>
    </div>
</header>