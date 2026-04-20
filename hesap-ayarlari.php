<?php
require_once __DIR__ . '/db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: auth.php?donus=hesap-ayarlari.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$error_message = '';
$success_message = '';

$has_phone_column = false;
if ($mysqli) {
    $col_res = $mysqli->query("SHOW COLUMNS FROM users LIKE 'telefon'");
    $has_phone_column = $col_res && $col_res->num_rows > 0;
    if ($col_res) {
        $col_res->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$mysqli) {
        $error_message = 'Veritabanı bağlantısı kurulamadı.';
    } elseif (isset($_POST['profil_guncelle'])) {
        $ad_soyad = trim((string) ($_POST['ad_soyad'] ?? ''));
        $telefon = trim((string) ($_POST['telefon'] ?? ''));

        if ($ad_soyad === '') {
            $error_message = 'Ad Soyad alanı boş bırakılamaz.';
        } else {
            if ($has_phone_column) {
                $stmt = $mysqli->prepare('UPDATE users SET ad_soyad = ?, telefon = ? WHERE id = ? LIMIT 1');
                if ($stmt) {
                    $stmt->bind_param('ssi', $ad_soyad, $telefon, $user_id);
                    if ($stmt->execute()) {
                        $_SESSION['user_name'] = $ad_soyad;
                        header('Location: hesap-ayarlari.php?durum=profil-ok');
                        exit;
                    }
                    $stmt->close();
                }
            } else {
                $stmt = $mysqli->prepare('UPDATE users SET ad_soyad = ? WHERE id = ? LIMIT 1');
                if ($stmt) {
                    $stmt->bind_param('si', $ad_soyad, $user_id);
                    if ($stmt->execute()) {
                        $_SESSION['user_name'] = $ad_soyad;
                        header('Location: hesap-ayarlari.php?durum=profil-ok');
                        exit;
                    }
                    $stmt->close();
                }
            }
            if ($error_message === '') {
                $error_message = 'Profil güncellenirken bir sorun oluştu.';
            }
        }
    } elseif (isset($_POST['sifre_degistir'])) {
        $eski_sifre = (string) ($_POST['eski_sifre'] ?? '');
        $yeni_sifre = (string) ($_POST['yeni_sifre'] ?? '');
        $yeni_sifre_tekrar = (string) ($_POST['yeni_sifre_tekrar'] ?? '');

        if ($eski_sifre === '' || $yeni_sifre === '' || $yeni_sifre_tekrar === '') {
            $error_message = 'Şifre alanlarının tamamını doldurun.';
        } elseif (strlen($yeni_sifre) < 6) {
            $error_message = 'Yeni şifre en az 6 karakter olmalı.';
        } elseif ($yeni_sifre !== $yeni_sifre_tekrar) {
            $error_message = 'Yeni şifre alanları eşleşmiyor.';
        } else {
            $stmt = $mysqli->prepare('SELECT sifre FROM users WHERE id = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res ? $res->fetch_assoc() : null;
                $stmt->close();

                if (!$row || !password_verify($eski_sifre, $row['sifre'])) {
                    $error_message = 'Eski şifreniz doğrulanamadı.';
                } else {
                    $yeni_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
                    $upd = $mysqli->prepare('UPDATE users SET sifre = ? WHERE id = ? LIMIT 1');
                    if ($upd) {
                        $upd->bind_param('si', $yeni_hash, $user_id);
                        if ($upd->execute()) {
                            header('Location: hesap-ayarlari.php?durum=sifre-ok');
                            exit;
                        }
                        $upd->close();
                    }
                    if ($error_message === '') {
                        $error_message = 'Şifre güncellenirken bir sorun oluştu.';
                    }
                }
            } else {
                $error_message = 'Şifre güncellemesi için hazırlık yapılamadı.';
            }
        }
    }
}

$select_sql = $has_phone_column
    ? 'SELECT ad_soyad, email, telefon FROM users WHERE id = ? LIMIT 1'
    : 'SELECT ad_soyad, email FROM users WHERE id = ? LIMIT 1';
$kullanici = null;
if ($mysqli) {
    $user_stmt = $mysqli->prepare($select_sql);
    if ($user_stmt) {
        $user_stmt->bind_param('i', $user_id);
        $user_stmt->execute();
        $user_res = $user_stmt->get_result();
        $kullanici = $user_res ? $user_res->fetch_assoc() : null;
        $user_stmt->close();
    }
}

if (!$kullanici) {
    header('Location: logout.php');
    exit;
}

if (isset($_GET['durum'])) {
    if ($_GET['durum'] === 'profil-ok' || $_GET['durum'] === 'sifre-ok') {
        $success_message = 'Bilgileriniz güncellendi.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hesap Ayarları — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css?v=20260420-1">
</head>
<body class="alt-page hesap-page">

<?php include __DIR__ . '/navbar.php'; ?>

<section class="page-banner">
<h1>Hesap Ayarları</h1>
<p>Kişisel bilgilerinizi güncelleyin ve şifrenizi güvenle değiştirin.</p>
</section>

<main class="hesap-shell">
<?php if ($error_message !== ''): ?>
<div class="hesap-alert hesap-alert--hata" role="alert"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
<?php if ($success_message !== ''): ?>
<div class="hesap-alert hesap-alert--ok" role="status"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>

<div class="hesap-grid">
    <section class="hesap-card">
        <h2><i class="fa-solid fa-user-cog" aria-hidden="true"></i> Hesap Bilgilerim</h2>
        <form method="post" class="hesap-form" autocomplete="on">
            <label for="ad_soyad">Ad Soyad</label>
            <input type="text" id="ad_soyad" name="ad_soyad" required autocomplete="name" value="<?php echo htmlspecialchars((string) $kullanici['ad_soyad'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="email">E-posta</label>
            <input type="email" id="email" name="email" disabled value="<?php echo htmlspecialchars((string) $kullanici['email'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="telefon">Telefon</label>
            <input type="tel" id="telefon" name="telefon" autocomplete="tel" placeholder="+90 5xx xxx xx xx" value="<?php echo htmlspecialchars((string) ($kullanici['telefon'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit" name="profil_guncelle" value="1" class="hesap-btn">Bilgileri Güncelle</button>
        </form>
    </section>

    <section class="hesap-card">
        <h2><i class="fa-solid fa-key" aria-hidden="true"></i> Şifre Değiştir</h2>
        <form method="post" class="hesap-form" autocomplete="off">
            <label for="eski_sifre">Eski Şifre</label>
            <input type="password" id="eski_sifre" name="eski_sifre" required autocomplete="current-password">

            <label for="yeni_sifre">Yeni Şifre</label>
            <input type="password" id="yeni_sifre" name="yeni_sifre" required minlength="6" autocomplete="new-password">

            <label for="yeni_sifre_tekrar">Yeni Şifre (Tekrar)</label>
            <input type="password" id="yeni_sifre_tekrar" name="yeni_sifre_tekrar" required minlength="6" autocomplete="new-password">

            <button type="submit" name="sifre_degistir" value="1" class="hesap-btn">Şifreyi Güncelle</button>
        </form>
    </section>
</div>
</main>

<?php include __DIR__ . '/footer.php'; ?>

</body>
</html>
