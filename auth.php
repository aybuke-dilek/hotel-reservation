<?php
require_once __DIR__ . '/db.php';

$auth_error = '';
$auth_success = '';

$donus = isset($_GET['donus']) ? $_GET['donus'] : '';
if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.php$/', $donus)) {
    $donus = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donus'])) {
    $pd = trim((string) $_POST['donus']);
    if (preg_match('/^[a-zA-Z0-9_\-\.]+\.php$/', $pd)) {
        $donus = $pd;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$mysqli) {
        $auth_error = 'Veritabanı bağlantısı kurulamadı. XAMPP ve veritabanı ayarlarınızı kontrol edin.';
    } elseif (isset($_POST['register'])) {
        $ad_soyad = isset($_POST['ad_soyad']) ? trim((string) $_POST['ad_soyad']) : '';
        $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
        $sifre = isset($_POST['sifre']) ? (string) $_POST['sifre'] : '';
        $sifre2 = isset($_POST['sifre_tekrar']) ? (string) $_POST['sifre_tekrar'] : '';

        if ($ad_soyad === '' || $email === '' || $sifre === '') {
            $auth_error = 'Lütfen tüm alanları doldurun.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $auth_error = 'Geçerli bir e-posta adresi girin.';
        } elseif (strlen($sifre) < 6) {
            $auth_error = 'Şifre en az 6 karakter olmalıdır.';
        } elseif ($sifre !== $sifre2) {
            $auth_error = 'Şifreler eşleşmiyor.';
        } else {
            $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $auth_error = 'Bu e-posta ile zaten kayıt var.';
                }
                $stmt->close();
            }
            if ($auth_error === '') {
                $hash = password_hash($sifre, PASSWORD_DEFAULT);
                $ins = $mysqli->prepare('INSERT INTO users (ad_soyad, email, sifre) VALUES (?, ?, ?)');
                if ($ins) {
                    $ins->bind_param('sss', $ad_soyad, $email, $hash);
                    if ($ins->execute()) {
                        $auth_success = 'Kayıt tamamlandı. Giriş yapabilirsiniz.';
                    } else {
                        $auth_error = 'Kayıt sırasında bir hata oluştu. Tabloyu oluşturduğunuzdan emin olun.';
                    }
                    $ins->close();
                } else {
                    $auth_error = 'Kayıt sırasında bir hata oluştu. Tabloyu oluşturduğunuzdan emin olun.';
                }
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = isset($_POST['login_email']) ? trim((string) $_POST['login_email']) : '';
        $sifre = isset($_POST['login_sifre']) ? (string) $_POST['login_sifre'] : '';

        if ($email === '' || $sifre === '') {
            $auth_error = 'E-posta ve şifre girin.';
        } else {
            $stmt = $mysqli->prepare('SELECT id, ad_soyad, sifre FROM users WHERE email = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res ? $res->fetch_assoc() : null;
                $stmt->close();
                if ($row && password_verify($sifre, $row['sifre'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int) $row['id'];
                    $_SESSION['user_name'] = $row['ad_soyad'];
                    $hedef = $donus !== '' ? $donus : 'index.php';
                    header('Location: ' . $hedef);
                    exit;
                }
            }
            $auth_error = 'E-posta veya şifre hatalı.';
        }
    }
}

$auth_tab = 'giris';
if (isset($_POST['register']) || ($auth_success !== '' && !isset($_POST['login']))) {
    $auth_tab = 'kayit';
}

if (file_exists(__DIR__ . '/images/hotel.jpg')) {
    $auth_bg = 'images/hotel.jpg';
} elseif (file_exists(__DIR__ . '/images/otel.jpeg')) {
    $auth_bg = 'images/otel.jpeg';
} else {
    $auth_bg = '';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Giriş &amp; Kayıt — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body class="alt-page auth-page"<?php echo $auth_bg !== '' ? ' style="--auth-bg-image:url(\'' . htmlspecialchars($auth_bg, ENT_QUOTES, 'UTF-8') . '\')"' : ''; ?>>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="auth-govselli">
<div class="auth-bg-img" aria-hidden="true"></div>
<div class="auth-bg-overlay" aria-hidden="true"></div>

<main class="auth-shell">
<div class="auth-kart auth-kart--anim">
<p class="auth-marka">Nova Hotel</p>
<h1 class="auth-baslik">Hesabınız</h1>
<p class="auth-alt-metin">Giriş yapın veya yeni hesap oluşturun.</p>

<?php if ($auth_error !== ''): ?>
<div class="auth-alert auth-alert--hata" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> <?php echo htmlspecialchars($auth_error, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
<?php if ($auth_success !== ''): ?>
<div class="auth-alert auth-alert--ok" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> <?php echo htmlspecialchars($auth_success, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>

<div class="auth-sekmeler" role="tablist" aria-label="Giriş veya kayıt">
<button type="button" class="auth-sekme<?php echo $auth_tab === 'giris' ? ' auth-sekme--aktif' : ''; ?>" role="tab" id="tab-auth-giris" aria-selected="<?php echo $auth_tab === 'giris' ? 'true' : 'false'; ?>" aria-controls="auth-panel-giris" data-auth-tab="giris">Giriş Yap</button>
<button type="button" class="auth-sekme<?php echo $auth_tab === 'kayit' ? ' auth-sekme--aktif' : ''; ?>" role="tab" id="tab-auth-kayit" aria-selected="<?php echo $auth_tab === 'kayit' ? 'true' : 'false'; ?>" aria-controls="auth-panel-kayit" data-auth-tab="kayit">Kayıt Ol</button>
</div>

<div id="auth-panel-giris" class="auth-panel" role="tabpanel" aria-labelledby="tab-auth-giris" data-auth-panel="giris"<?php echo $auth_tab !== 'giris' ? ' hidden' : ''; ?>>
<form class="auth-form" method="post" action="auth.php" autocomplete="on">
<?php if ($donus !== ''): ?><input type="hidden" name="donus" value="<?php echo htmlspecialchars($donus, ENT_QUOTES, 'UTF-8'); ?>"><?php endif; ?>
<div class="auth-field">
<label for="login_email">E-posta</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
<input class="auth-input" type="email" id="login_email" name="login_email" required placeholder="ornek@email.com" autocomplete="email" value="<?php echo isset($_POST['login_email']) ? htmlspecialchars((string) $_POST['login_email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
</div>
</div>
<div class="auth-field">
<label for="login_sifre">Şifre</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
<input class="auth-input" type="password" id="login_sifre" name="login_sifre" required placeholder="••••••••" autocomplete="current-password">
</div>
</div>
<button type="submit" name="login" value="1" class="auth-btn">Giriş Yap</button>
</form>
</div>

<div id="auth-panel-kayit" class="auth-panel" role="tabpanel" aria-labelledby="tab-auth-kayit" data-auth-panel="kayit"<?php echo $auth_tab !== 'kayit' ? ' hidden' : ''; ?>>
<form class="auth-form" method="post" action="auth.php" autocomplete="on">
<?php if ($donus !== ''): ?><input type="hidden" name="donus" value="<?php echo htmlspecialchars($donus, ENT_QUOTES, 'UTF-8'); ?>"><?php endif; ?>
<div class="auth-field">
<label for="ad_soyad">Ad Soyad</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
<input class="auth-input" type="text" id="ad_soyad" name="ad_soyad" required placeholder="Adınız Soyadınız" autocomplete="name" value="<?php echo isset($_POST['ad_soyad']) ? htmlspecialchars((string) $_POST['ad_soyad'], ENT_QUOTES, 'UTF-8') : ''; ?>">
</div>
</div>
<div class="auth-field">
<label for="email">E-posta</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
<input class="auth-input" type="email" id="email" name="email" required placeholder="ornek@email.com" autocomplete="email" value="<?php echo isset($_POST['email']) && !isset($_POST['login']) ? htmlspecialchars((string) $_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
</div>
</div>
<div class="auth-field">
<label for="sifre">Şifre</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
<input class="auth-input" type="password" id="sifre" name="sifre" required minlength="6" placeholder="En az 6 karakter" autocomplete="new-password">
</div>
</div>
<div class="auth-field">
<label for="sifre_tekrar">Şifre (tekrar)</label>
<div class="auth-input-wrap">
<span class="auth-input-ikon" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
<input class="auth-input" type="password" id="sifre_tekrar" name="sifre_tekrar" required minlength="6" placeholder="Şifrenizi tekrarlayın" autocomplete="new-password">
</div>
</div>
<button type="submit" name="register" value="1" class="auth-btn">Hesap Oluştur</button>
</form>
</div>

<p class="auth-alt-link"><a href="index.php"><i class="fa-solid fa-arrow-left"></i> Ana sayfaya dön</a><?php if ($donus === 'rezervasyon.php'): ?> · <a href="rezervasyon.php">Rezervasyona dön</a><?php endif; ?></p>
</div>
</main>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<script>
(function () {
    var tabs = document.querySelectorAll('[data-auth-tab]');
    var panels = document.querySelectorAll('[data-auth-panel]');
    function activate(name) {
        tabs.forEach(function (t) {
            var on = t.getAttribute('data-auth-tab') === name;
            t.classList.toggle('auth-sekme--aktif', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach(function (p) {
            var on = p.getAttribute('data-auth-panel') === name;
            if (on) {
                p.removeAttribute('hidden');
            } else {
                p.setAttribute('hidden', '');
            }
        });
    }
    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            activate(t.getAttribute('data-auth-tab'));
        });
    });
})();
</script>
</body>
</html>
