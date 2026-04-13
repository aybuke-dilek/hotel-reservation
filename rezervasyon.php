<?php
require_once __DIR__ . '/db.php';
$rezervasyon_girisli = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rezervasyon — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="alt-page rezervasyon-page">

<?php include __DIR__ . '/navbar.php'; ?>

<section class="page-banner" id="rezervasyon-ust">
<h1>Rezervasyon</h1>
<p>Tarihlerinizi ve oda tercihlerinizi seçin; gecelik tutar anında hesaplanır.</p>
</section>

<?php if (!$rezervasyon_girisli): ?>
<section class="rez-giris-uyari" aria-live="polite">
<div class="rez-giris-uyari-icerik">
<i class="fa-solid fa-lock" aria-hidden="true"></i>
<div>
<h2>Rezervasyon için giriş yapmalısınız</h2>
<p>Rezervasyon formunu kullanmak için lütfen hesabınıza giriş yapın veya yeni hesap oluşturun.</p>
<p class="rez-giris-uyari-aksiyon"><a class="rez-giris-uyari-btn" href="auth.php?donus=rezervasyon.php">Giriş / Kayıt</a> <a class="rez-giris-uyari-link" href="index.php">Ana sayfa</a></p>
</div>
</div>
</section>
<?php else: ?>

<main class="rezervasyon-shell" id="rezervasyonApp">
<aside class="rez-ozet" data-aos="fade-right" aria-labelledby="rez-ozet-baslik">
<h2 id="rez-ozet-baslik">Seçim Özeti</h2>
<p class="rez-ozet-alt">Seçimleriniz kaybolmaz; adımlar arasında güncellenir.</p>
<dl class="rez-ozet-liste">
<div class="rez-ozet-satir"><dt>Giriş — Çıkış</dt><dd id="ozetTarih">—</dd></div>
<div class="rez-ozet-satir"><dt>Gece sayısı</dt><dd id="ozetGece">—</dd></div>
<div class="rez-ozet-satir"><dt>Misafir</dt><dd id="ozetMisafir">—</dd></div>
<div class="rez-ozet-satir"><dt>Oda tipi</dt><dd id="ozetOdaTip">—</dd></div>
<div class="rez-ozet-satir"><dt>Kat / Oda no</dt><dd id="ozetKatOda">—</dd></div>
<div class="rez-ozet-satir"><dt>Manzara</dt><dd id="ozetManzara">—</dd></div>
<div class="rez-ozet-satir"><dt>Yatak</dt><dd id="ozetYatak">—</dd></div>
<div class="rez-ozet-satir"><dt>Ekstralar</dt><dd id="ozetEkstra">—</dd></div>
</dl>
<div class="rez-ozet-fiyat-kutu">
<span class="rez-ozet-fiyat-etiket">Tahmini toplam</span>
<strong id="ozetToplam" class="rez-ozet-fiyat-tutar">0 ₺</strong>
<span id="ozetGecelik" class="rez-ozet-gecelik">Gecelik: —</span>
</div>
<div class="rez-ozet-iletisim">
<p><i class="fa-solid fa-phone"></i> +90 216 555 00 00</p>
<p><i class="fa-solid fa-envelope"></i> info@novahotel.com</p>
</div>
</aside>

<div class="rez-sag" data-aos="fade-left">
<div class="rez-oda-panel" id="rezOdaPanel" aria-live="polite">
<div class="rez-oda-panel-gorsel">
<img id="rezRoomImg" src="images/1.jpeg" alt="Seçilen oda tipi">
</div>
<div class="rez-oda-panel-metin">
<h3 id="rezRoomTitle">Standart Oda</h3>
<ul class="rez-oda-panel-oz" id="rezRoomSpecs">
<li><i class="fa-solid fa-ruler-combined"></i> <span>22 m²</span></li>
<li><i class="fa-solid fa-user"></i> <span>2 kişi</span></li>
<li><i class="fa-solid fa-bed"></i> <span>Çift kişilik</span></li>
</ul>
<p class="rez-oda-panel-not" id="rezRoomDesc">Ada manzaralı standart oda.</p>
</div>
</div>

<form class="rez-form" id="rezForm" action="#" method="get" novalidate>
<div class="rez-stepper-ust">
<div class="rez-adimlar" role="tablist" aria-label="Rezervasyon adımları">
<button type="button" class="rez-adim aktif" data-step="1" role="tab" aria-selected="true" id="tab-step-1"><span class="rez-adim-no">1</span><span class="rez-adim-txt">Tarih &amp; misafir</span></button>
<button type="button" class="rez-adim" data-step="2" role="tab" aria-selected="false" id="tab-step-2"><span class="rez-adim-no">2</span><span class="rez-adim-txt">Oda özellikleri</span></button>
<button type="button" class="rez-adim" data-step="3" role="tab" aria-selected="false" id="tab-step-3"><span class="rez-adim-no">3</span><span class="rez-adim-txt">İletişim</span></button>
</div>
</div>

<div class="rez-adim-panel" data-step-panel="1" role="tabpanel" aria-labelledby="tab-step-1">
<label for="giris">Giriş tarihi</label>
<input type="date" id="giris" name="giris" required>

<label for="cikis">Çıkış tarihi</label>
<input type="date" id="cikis" name="cikis" required>

<label for="yetiskin">Yetişkin</label>
<input type="number" id="yetiskin" name="yetiskin" min="1" max="8" value="2" required>

<label for="cocuk">Çocuk</label>
<input type="number" id="cocuk" name="cocuk" min="0" max="6" value="0">

<div class="rez-adim-nav">
<button type="button" class="rez-btn rez-btn-ileri" data-next="2">Devam</button>
</div>
</div>

<div class="rez-adim-panel gizle" data-step-panel="2" role="tabpanel" aria-labelledby="tab-step-2" hidden>
<label for="odaTip">Oda tipi</label>
<select id="odaTip" name="odaTip" required>
<option value="standart">Standart</option>
<option value="deluxe">Deluxe</option>
<option value="suite">Suite</option>
</select>

<label for="kat">Kat (1 — 10)</label>
<select id="kat" name="kat" required></select>

<label for="odaNo">Oda numarası</label>
<select id="odaNo" name="odaNo" required></select>
<p class="rez-yardim" id="odaAralikYardim">Kat seçildiğinde oda aralığı güncellenir.</p>

<label for="manzara">Manzara</label>
<select id="manzara" name="manzara" required>
<option value="kara">Kara / Şehir</option>
<option value="deniz">Deniz</option>
<option value="havuz">Havuz</option>
</select>

<label for="yatakTip">Yatak tipi</label>
<select id="yatakTip" name="yatakTip" required>
<option value="king">Tek büyük yatak (King)</option>
<option value="twin">İki ayrı yatak (Twin)</option>
</select>

<fieldset class="rez-ekstra-set">
<legend>Ekstralar</legend>
<label class="rez-check"><input type="checkbox" name="jakuzi" id="ekJakuzi"> Jakuzi</label>
<label class="rez-check"><input type="checkbox" name="balkon" id="ekBalkon"> Balkon</label>
<label class="rez-check"><input type="checkbox" name="bebekYatagi" id="ekBebek"> Bebek yatağı</label>
<span class="rez-sigara-grup">
<span class="rez-sigara-etiket">Sigara</span>
<label class="rez-radio"><input type="radio" name="sigara" value="icilmez" checked> İçilemez</label>
<label class="rez-radio"><input type="radio" name="sigara" value="icilebilir"> İçilebilir</label>
</span>
</fieldset>

<div class="rez-adim-nav">
<button type="button" class="rez-btn rez-btn-geri" data-prev="1">Geri</button>
<button type="button" class="rez-btn rez-btn-ileri" data-next="3">Devam</button>
</div>
</div>

<div class="rez-adim-panel gizle" data-step-panel="3" role="tabpanel" aria-labelledby="tab-step-3" hidden>
<label for="adSoyad">Ad Soyad</label>
<input type="text" id="adSoyad" name="adSoyad" required placeholder="Adınız Soyadınız" autocomplete="name">

<label for="eposta">E-posta</label>
<input type="email" id="eposta" name="eposta" required placeholder="ornek@email.com" autocomplete="email">

<label for="telefon">Telefon</label>
<input type="tel" id="telefon" name="telefon" required placeholder="+90 5xx xxx xx xx" autocomplete="tel">

<label for="notlar">Özel istekler (isteğe bağlı)</label>
<textarea id="notlar" name="notlar" rows="3" placeholder="Geç giriş, kutlama vb."></textarea>

<div class="rez-adim-nav">
<button type="button" class="rez-btn rez-btn-geri" data-prev="2">Geri</button>
<button type="submit" class="rez-btn rez-btn-submit">Rezervasyonu tamamla</button>
</div>
</div>
</form>
</div>
</main>

<div id="rezModal" class="rez-modal" role="dialog" aria-modal="true" aria-labelledby="rezModalBaslik" aria-describedby="rezModalMesaj" aria-hidden="true" hidden>
<div class="rez-modal__backdrop" tabindex="-1"></div>
<div class="rez-modal__kutu">
<div class="rez-modal__ust">
<span class="rez-modal__ikon" id="rezModalIkon" aria-hidden="true"><i class="fa-solid fa-circle-exclamation"></i></span>
<h2 id="rezModalBaslik" class="rez-modal__baslik">Nova Hotel</h2>
</div>
<p id="rezModalMesaj" class="rez-modal__mesaj"></p>
<button type="button" class="rez-modal__btn" id="rezModalKapat">Tamam</button>
</div>
</div>

<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="script.js"></script>
</body>
</html>
