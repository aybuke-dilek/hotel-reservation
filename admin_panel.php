<?php
require_once __DIR__ . '/db.php';

if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    header('Location: admin_login.php');
    exit;
}

if (empty($_SESSION['is_admin'])) {
    header('Location: admin_login.php');
    exit;
}

$hata = '';
$bilgi = '';
$rezervasyonlar = [];

if (!$mysqli || $mysqli->connect_errno) {
    $hata = 'Veritabani baglantisi kurulamadi.';
}

$alanlar = [];
if ($hata === '') {
    $kolonSonuc = $mysqli->query("SHOW COLUMNS FROM rezervasyonlar");
    if ($kolonSonuc instanceof mysqli_result) {
        while ($satir = $kolonSonuc->fetch_assoc()) {
            $alanlar[] = $satir['Field'];
        }
        $kolonSonuc->free();
    } else {
        $hata = 'rezervasyonlar tablosu bulunamadi veya kolonlar okunamadi.';
    }
}

$kolonSec = static function (array $varOlanlar, array $alternatifler): ?string {
    foreach ($alternatifler as $alan) {
        if (in_array($alan, $varOlanlar, true)) {
            return $alan;
        }
    }
    return null;
};

$idKolon = $kolonSec($alanlar, ['id', 'rezervasyon_id']);
$durumKolon = $kolonSec($alanlar, ['durum', 'status']);
$musteriKolon = $kolonSec($alanlar, ['musteri_adi', 'ad_soyad', 'adsoyad', 'isim', 'ad']);
$odaKolon = $kolonSec($alanlar, ['oda_tipi', 'oda', 'room_type']);
$girisKolon = $kolonSec($alanlar, ['giris_tarihi', 'giris', 'check_in', 'checkin']);
$cikisKolon = $kolonSec($alanlar, ['cikis_tarihi', 'cikis', 'check_out', 'checkout']);
$toplamKolon = $kolonSec($alanlar, ['toplam_fiyat', 'toplam_ucret', 'toplam', 'fiyat', 'total_price']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $hata === '') {
    if (!$idKolon || !$durumKolon) {
        $hata = 'Durum guncellemek icin gerekli kolonlar (id ve durum) bulunamadi.';
    } else {
        $rezervasyonId = (int)($_POST['rezervasyon_id'] ?? 0);
        $aksiyon = (string)($_POST['aksiyon'] ?? '');

        if ($rezervasyonId <= 0 || !in_array($aksiyon, ['onayla', 'reddet'], true)) {
            $hata = 'Gecersiz istek.';
        } else {
            $yeniDurum = $aksiyon === 'onayla' ? 'Onaylandi' : 'Reddedildi';
            $sql = "UPDATE rezervasyonlar SET `$durumKolon` = ? WHERE `$idKolon` = ? LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('si', $yeniDurum, $rezervasyonId);
                if ($stmt->execute()) {
                    $bilgi = 'Rezervasyon durumu guncellendi.';
                } else {
                    $hata = 'Durum guncellenirken hata olustu.';
                }
                $stmt->close();
            } else {
                $hata = 'Durum guncelleme sorgusu hazirlanamadi.';
            }
        }
    }
}

if ($hata === '') {
    $secimler = [
        ($idKolon ? "`$idKolon`" : '0') . ' AS rezervasyon_id',
        ($musteriKolon ? "`$musteriKolon`" : "''") . ' AS musteri_adi',
        ($odaKolon ? "`$odaKolon`" : "''") . ' AS oda_tipi',
        ($girisKolon ? "`$girisKolon`" : "''") . ' AS giris_tarihi',
        ($cikisKolon ? "`$cikisKolon`" : "''") . ' AS cikis_tarihi',
        ($toplamKolon ? "`$toplamKolon`" : '0') . ' AS toplam_fiyat',
        ($durumKolon ? "`$durumKolon`" : "''") . ' AS durum',
    ];

    $sirala = $idKolon ? "ORDER BY `$idKolon` DESC" : '';
    $listeSql = 'SELECT ' . implode(', ', $secimler) . " FROM rezervasyonlar $sirala";
    $listeSonuc = $mysqli->query($listeSql);

    if ($listeSonuc instanceof mysqli_result) {
        while ($satir = $listeSonuc->fetch_assoc()) {
            $rezervasyonlar[] = $satir;
        }
        $listeSonuc->free();
    } else {
        $hata = 'Rezervasyon listesi alinamadi.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Paneli - Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css?v=20260420-1">
</head>
<body class="admin-panel-page">
<header class="admin-topbar">
    <div class="admin-topbar-brand">
        <span class="admin-topbar-logo">NOVA HOTEL</span>
        <span class="admin-topbar-sep"></span>
        <strong>Admin Paneli</strong>
    </div>
    <a class="admin-topbar-exit" href="admin_panel.php?logout=1">Cikis</a>
</header>

<main class="admin-panel-wrap">
    <section class="admin-panel-card">
        <div class="admin-panel-head">
            <h1>Rezervasyonlar</h1>
            <span class="admin-count-pill"><?php echo count($rezervasyonlar); ?> Kayit</span>
        </div>

        <?php if ($hata !== ''): ?>
            <p class="admin-feedback admin-feedback--error"><?php echo htmlspecialchars($hata, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if ($bilgi !== ''): ?>
            <p class="admin-feedback admin-feedback--success"><?php echo htmlspecialchars($bilgi, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Musteri Adi</th>
                        <th>Oda Tipi</th>
                        <th>Giris/Cikis Tarihi</th>
                        <th>Toplam Fiyat</th>
                        <th>Durum</th>
                        <th>Islem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rezervasyonlar)): ?>
                        <tr>
                            <td colspan="6" class="admin-table-empty">Gosterilecek rezervasyon kaydi bulunamadi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rezervasyonlar as $rez): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$rez['musteri_adi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars((string)$rez['oda_tipi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php
                                    $giris = (string)$rez['giris_tarihi'];
                                    $cikis = (string)$rez['cikis_tarihi'];
                                    echo htmlspecialchars(trim($giris . ' - ' . $cikis, ' -'), ENT_QUOTES, 'UTF-8');
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars((string)$rez['toplam_fiyat'], ENT_QUOTES, 'UTF-8'); ?> TL</td>
                                <?php
                                $durumHam = trim((string)$rez['durum']);
                                $durumNorm = strtolower($durumHam);
                                $isOnayli = in_array($durumNorm, ['onaylandi', 'onaylandı'], true);
                                $isReddedildi = in_array($durumNorm, ['reddedildi'], true);
                                $isBeklemede = !$isOnayli && !$isReddedildi;
                                $durumSinif = $isOnayli ? 'admin-durum admin-durum--onayli' : ($isReddedildi ? 'admin-durum admin-durum--reddedildi' : 'admin-durum admin-durum--beklemede');
                                $durumYazi = $isOnayli ? 'Onaylandi' : ($isReddedildi ? 'Reddedildi' : ($durumHam !== '' ? $durumHam : 'Beklemede'));
                                ?>
                                <td><span class="<?php echo $durumSinif; ?>"><?php echo htmlspecialchars($durumYazi, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td>
                                    <?php if ($idKolon && $durumKolon && $isBeklemede): ?>
                                        <form method="post" class="admin-action-form">
                                            <input type="hidden" name="rezervasyon_id" value="<?php echo (int)$rez['rezervasyon_id']; ?>">
                                            <button type="submit" name="aksiyon" value="onayla" class="admin-btn admin-btn--approve">Onayla</button>
                                            <button type="submit" name="aksiyon" value="reddet" class="admin-btn admin-btn--reject">Reddet</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="admin-table-empty">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
