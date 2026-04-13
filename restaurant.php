<?php require_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Restoran — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="alt-page">

<?php include __DIR__ . '/navbar.php'; ?>

<section class="restaurant-page-ambiance" data-aos="fade-up">
<img src="images/r1.jpeg" alt="Nova Hotel restoran ambiyansı">
<div class="ambiance-metin">
<h2>Marmara esintisi eşliğinde sofistane bir yemek deneyimi</h2>
<p>Doğal ışık alan salonumuz, deniz manzaralı terasımız ve özenle seçilmiş müzik seçkisiyle akşam yemekleri unutulmaz bir ritüele dönüşüyor.</p>
</div>
</section>

<div class="restaurant-chef-wrap" data-aos="fade-up">
<div class="restaurant-chef-kutu">
<div class="chef-ikon"><i class="fa-solid fa-utensils"></i></div>
<div>
<span class="chef-etiket">Şefin tavsiyesi</span>
<h3>Adalar usulü levrek ve safranlı risotto</h3>
<p>Günün taze avından seçilen levrek, fırında roka ve limonla harmanlanıyor; yanında ise yerel peynirlerle zenginleştirilmiş safranlı risotto eşlik ediyor. Şarap eşleştirmesi için sommelier ekibimize danışabilirsiniz.</p>
</div>
</div>
</div>

<section class="restaurant-menu-bolum" data-aos="fade-up">
<h2>Menü</h2>
<div class="menu-sekmeler" role="tablist">
<button type="button" class="aktif" data-menu="menu-kahvalti" role="tab" aria-selected="true">Kahvaltı</button>
<button type="button" data-menu="menu-ogle" role="tab" aria-selected="false">Öğle Yemeği</button>
<button type="button" data-menu="menu-aksam" role="tab" aria-selected="false">Akşam Yemeği</button>
</div>

<div id="menu-kahvalti" class="menu-panel aktif" role="tabpanel">
<ul>
<li class="menu-oge"><div><div class="menu-oge-baslik">Serpme Kahvaltı</div><div class="menu-oge-aciklama">Yöresel peynirler, zeytinyağlılar, reçeller ve sıcak börekler</div></div><span class="menu-oge-fiyat">450 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Menemen &amp; Sucuklu Yumurta</div><div class="menu-oge-aciklama">Köy yumurtası, domates-biber, ev yapımı ekmek</div></div><span class="menu-oge-fiyat">220 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Avokado &amp; Lor Peynirli Tost</div><div class="menu-oge-aciklama">Tam buğday, cherry domates, çekirdek mix</div></div><span class="menu-oge-fiyat">280 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Pankek &amp; Akçaağaç</div><div class="menu-oge-aciklama">Taze mevsim meyveleri ve çırpılmış krema</div></div><span class="menu-oge-fiyat">240 ₺</span></li>
</ul>
</div>

<div id="menu-ogle" class="menu-panel" role="tabpanel">
<ul>
<li class="menu-oge"><div><div class="menu-oge-baslik">Deniz Ürünleri Çorbası</div><div class="menu-oge-aciklama">Günün balığı ve midye ile klasik ada tarifi</div></div><span class="menu-oge-fiyat">185 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Izgara Çipura</div><div class="menu-oge-aciklama">Roka salatası, limonlu zeytinyağı</div></div><span class="menu-oge-fiyat">520 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Kuzu İncik</div><div class="menu-oge-aciklama">Fırın patates, sezon sebzeleri, redüksiyon sos</div></div><span class="menu-oge-fiyat">680 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Vejeteryan Musakka</div><div class="menu-oge-aciklama">Fırınlanmış patlıcan, nohut, domates sosu</div></div><span class="menu-oge-fiyat">360 ₺</span></li>
</ul>
</div>

<div id="menu-aksam" class="menu-panel" role="tabpanel">
<ul>
<li class="menu-oge"><div><div class="menu-oge-baslik">Şefin Tasting Menüsü (5 araç)</div><div class="menu-oge-aciklama">Mevsimsel ürünlerle günlük değişen seçki — vejetaryen alternatif mevcut</div></div><span class="menu-oge-fiyat">1.890 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Bonfile &amp; Trüf Patates</div><div class="menu-oge-aciklama">250 gr bonfile, tereyağlı püre, taze trüf rendesi</div></div><span class="menu-oge-fiyat">950 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Füme Somon Carpaccio</div><div class="menu-oge-aciklama">Misket limonu, dereotu kreması, kapari</div></div><span class="menu-oge-fiyat">420 ₺</span></li>
<li class="menu-oge"><div><div class="menu-oge-baslik">Çikolatalı sufle</div><div class="menu-oge-aciklama">Vanilyalı dondurma eşliğinde</div></div><span class="menu-oge-fiyat">220 ₺</span></li>
</ul>
</div>
</section>

<section class="restaurant-page-fotolar" data-aos="fade-up">
<h2>Lezzetlerimizden Kareler</h2>
<div class="foto-satir">
<img src="images/r7.jpeg" alt="Restoran tabağı">
<img src="images/r2.jpeg" alt="Yemek sunumu">
<img src="images/r3.jpeg" alt="Deniz ürünleri">
</div>
</section>

<?php include __DIR__ . '/footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="script.js"></script>
</body>
</html>
