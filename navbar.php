<header>
<div class="logo"><a href="index.php" class="logo-home"><i class="fa-regular fa-gem"></i> Nova Hotel</a></div>
<nav class="header-nav" aria-label="Ana menü">
<a href="index.php">Ana Sayfa</a>
<a href="restaurant.php">Restaurant</a>
<a href="galeri.php">Galeri</a>
<a href="odalar.php">Odalar</a>
<a href="rezervasyon.php">Rezervasyon</a>
<?php if (!empty($_SESSION['user_id'])): ?>
<div class="nav-user-area" role="group" aria-label="Oturum">
    <span class="nav-hosgeldin"><i class="fa-solid fa-circle-user" aria-hidden="true"></i><span class="nav-hosgeldin-metin">Hoş geldin, <strong><?php echo htmlspecialchars((string) ($_SESSION['user_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></span></span>
    <a href="logout.php" class="nav-cikis"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Çıkış</a>
</div>
<?php else: ?>
<a href="auth.php" class="nav-giris" title="Giriş yap"><i class="fa-solid fa-user-circle" aria-hidden="true"></i><span>Giriş Yap</span></a>
<?php endif; ?>
</nav>
</header>
