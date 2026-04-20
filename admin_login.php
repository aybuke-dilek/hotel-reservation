<?php
require_once __DIR__ . '/db.php';

if (!empty($_SESSION['is_admin'])) {
    header('Location: admin_panel.php');
    exit;
}

$hata = '';
$kullanici_adi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullanici_adi = trim((string)($_POST['username'] ?? ''));
    $sifre = (string)($_POST['password'] ?? '');

    if ($kullanici_adi === 'admin' && $sifre === 'admin123') {
        $_SESSION['is_admin'] = true;
        header('Location: admin_panel.php');
        exit;
    }

    $hata = 'Kullanici adi veya sifre hatali.';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Giris - Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css?v=20260420-1">
</head>
<body class="admin-login-page">
<main class="admin-login-wrap">
    <section class="admin-login-card">
        <p class="admin-login-kicker">Nova Hotel</p>
        <h1>Admin Girisi</h1>
        <p class="admin-login-subtitle">Sadece yetkili yonetim giris yapabilir.</p>

        <?php if ($hata !== ''): ?>
            <p class="admin-feedback admin-feedback--error"><?php echo htmlspecialchars($hata, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form method="post" class="admin-login-form">
            <label for="username">Kullanici Adi</label>
            <input type="text" id="username" name="username" required autocomplete="username" value="<?php echo htmlspecialchars($kullanici_adi, ENT_QUOTES, 'UTF-8'); ?>">

            <label for="password">Sifre</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit">Giris Yap</button>
        </form>
    </section>
</main>
</body>
</html>
