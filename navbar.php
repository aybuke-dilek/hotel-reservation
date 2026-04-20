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
    <div class="nav-user-dropdown">
        <button type="button" class="nav-user-toggle" aria-expanded="false" aria-haspopup="true" aria-label="Kullanıcı menüsünü aç">
            <span class="nav-hosgeldin"><i class="fa-solid fa-circle-user" aria-hidden="true"></i><span class="nav-hosgeldin-metin">Hoş geldin, <strong><?php echo htmlspecialchars((string) ($_SESSION['user_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></span></span>
            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
        </button>
        <div class="nav-user-menu" role="menu">
            <a href="rezervasyonlarim.php" class="nav-user-menu-item" role="menuitem"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Rezervasyonlarım</a>
            <a href="hesap-ayarlari.php" class="nav-user-menu-item" role="menuitem"><i class="fa-solid fa-user-cog" aria-hidden="true"></i> Hesap Ayarlarım</a>
            <a href="logout.php" class="nav-user-menu-item nav-user-menu-item--logout" role="menuitem"><i class="fa-solid fa-sign-out-alt" aria-hidden="true"></i> Güvenli Çıkış</a>
        </div>
    </div>
</div>
<?php else: ?>
<a href="auth.php" class="nav-giris" title="Giriş yap"><i class="fa-solid fa-user-circle" aria-hidden="true"></i><span>Giriş Yap</span></a>
<?php endif; ?>
</nav>
</header>

<script>
(function () {
    var dropdowns = document.querySelectorAll('.nav-user-dropdown');
    if (!dropdowns.length) {
        return;
    }

    dropdowns.forEach(function (dropdown) {
        var toggle = dropdown.querySelector('.nav-user-toggle');
        if (!toggle) {
            return;
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            dropdowns.forEach(function (other) {
                if (other !== dropdown) {
                    other.classList.remove('is-open');
                    var otherToggle = other.querySelector('.nav-user-toggle');
                    if (otherToggle) {
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
            var isOpen = dropdown.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        toggle.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                dropdown.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.addEventListener('click', function (event) {
        dropdowns.forEach(function (dropdown) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('is-open');
                var toggle = dropdown.querySelector('.nav-user-toggle');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
})();
</script>
