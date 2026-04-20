<?php
require_once __DIR__ . '/db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: auth.php?donus=rezervasyonlarim.php');
    exit;
}

$hata = '';
$rezervasyonlar = [];
$basariMesaji = isset($_GET['rez']) && $_GET['rez'] === 'ok'
    ? 'Rezervasyonunuz basariyla olusturuldu. Onay durumu icin bu sayfayi takip edebilirsiniz.'
    : '';

if (!($mysqli instanceof mysqli) || $mysqli->connect_errno) {
    $hata = 'Veritabani baglantisi kurulamadigi icin rezervasyonlar listelenemedi.';
} else {
    $alanlar = [];
    $kolonSonuc = $mysqli->query("SHOW COLUMNS FROM rezervasyonlar");
    if ($kolonSonuc instanceof mysqli_result) {
        while ($satir = $kolonSonuc->fetch_assoc()) {
            $alan = (string)($satir['Field'] ?? '');
            if ($alan !== '') {
                $alanlar[] = $alan;
            }
        }
        $kolonSonuc->free();
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
    $musteriKolon = $kolonSec($alanlar, ['musteri_adi', 'ad_soyad', 'adsoyad', 'isim', 'ad']);
    $odaKolon = $kolonSec($alanlar, ['oda_tipi', 'oda', 'room_type']);
    $girisKolon = $kolonSec($alanlar, ['giris_tarihi', 'giris', 'check_in', 'checkin']);
    $cikisKolon = $kolonSec($alanlar, ['cikis_tarihi', 'cikis', 'check_out', 'checkout']);
    $toplamKolon = $kolonSec($alanlar, ['toplam_fiyat', 'toplam_ucret', 'toplam', 'fiyat', 'total_price']);
    $durumKolon = $kolonSec($alanlar, ['durum', 'status']);
    $userIdKolon = $kolonSec($alanlar, ['user_id', 'kullanici_id', 'uye_id']);

    $whereSql = '';
    $whereTip = '';
    $whereDeger = null;
    if ($userIdKolon) {
        $whereSql = "WHERE `$userIdKolon` = ?";
        $whereTip = 'i';
        $whereDeger = (int)$_SESSION['user_id'];
    } elseif ($musteriKolon && !empty($_SESSION['user_name'])) {
        $whereSql = "WHERE `$musteriKolon` = ?";
        $whereTip = 's';
        $whereDeger = (string)$_SESSION['user_name'];
    } else {
        $hata = 'Rezervasyon tablosunda kullaniciya gore filtreleme icin uygun kolon bulunamadi.';
    }

    if ($hata === '') {
        $secimler = [
            ($musteriKolon ? "`$musteriKolon`" : "''") . ' AS musteri_adi',
            ($odaKolon ? "`$odaKolon`" : "''") . ' AS oda_tipi',
            ($girisKolon ? "`$girisKolon`" : "''") . ' AS giris_tarihi',
            ($cikisKolon ? "`$cikisKolon`" : "''") . ' AS cikis_tarihi',
            ($toplamKolon ? "`$toplamKolon`" : '0') . ' AS toplam_fiyat',
            ($durumKolon ? "`$durumKolon`" : "'Beklemede'") . ' AS durum',
        ];
        $sirala = $idKolon ? " ORDER BY `$idKolon` DESC" : '';
        $sql = 'SELECT ' . implode(', ', $secimler) . ' FROM rezervasyonlar ' . $whereSql . $sirala;
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            $hata = 'Rezervasyon listesi sorgusu hazirlanamadi.';
        } else {
            $stmt->bind_param($whereTip, $whereDeger);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res instanceof mysqli_result) {
                while ($satir = $res->fetch_assoc()) {
                    $rezervasyonlar[] = $satir;
                }
                $res->free();
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rezervasyonlarım — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css?v=20260420-1">
</head>
<body class="alt-page rezervasyonlarim-page">

<?php include __DIR__ . '/navbar.php'; ?>

<section class="page-banner">
<h1>Rezervasyonlarım</h1>
<p>Bu bölümden rezervasyonlarınızı takip edebilir, yeni rezervasyon oluşturabilirsiniz.</p>
</section>

<main class="hesap-shell">
<section class="hesap-card hesap-card--full">
    <h2><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Rezervasyon Yönetimi</h2>
    <?php if ($basariMesaji !== ''): ?>
    <div class="hesap-alert hesap-alert--ok" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> <?php echo htmlspecialchars($basariMesaji, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($hata !== ''): ?>
    <div class="hesap-alert hesap-alert--hata" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> <?php echo htmlspecialchars($hata, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php else: ?>
    <p class="hesap-aciklama">Onay, bekleme veya red durumundaki rezervasyonlarınızı bu listeden takip edebilirsiniz.</p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Müşteri Adı</th>
                    <th>Oda Tipi</th>
                    <th>Giriş/Çıkış Tarihi</th>
                    <th>Toplam Fiyat</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rezervasyonlar)): ?>
                    <tr><td colspan="5" class="admin-table-empty">Henüz rezervasyon kaydınız bulunmuyor.</td></tr>
                <?php else: ?>
                    <?php foreach ($rezervasyonlar as $rez): ?>
                        <tr>
                            <td><?php echo htmlspecialchars((string)$rez['musteri_adi'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string)$rez['oda_tipi'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(trim((string)$rez['giris_tarihi'] . ' - ' . (string)$rez['cikis_tarihi'], ' -'), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string)$rez['toplam_fiyat'], ENT_QUOTES, 'UTF-8'); ?> TL</td>
                            <td><?php echo htmlspecialchars((string)$rez['durum'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <a href="rezervasyon.php" class="hesap-btn hesap-btn--link">Yeni Rezervasyon Oluştur</a>
</section>
</main>

<?php include __DIR__ . '/footer.php'; ?>

</body>
</html>
