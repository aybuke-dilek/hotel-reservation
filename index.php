<?php require_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<link rel="stylesheet" href="style.css?v=20260420-1">
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="hero">
<div class="hero-icerik">
<h1 class="hero-animate-title">Marmara'nın Kalbinde Eşsiz Bir Keşif</h1>
<a href="rezervasyon.php" class="hero-cta hero-animate-cta">Hemen Rezervasyon Yap</a>
</div>
</div>

<div id="hakkimizda" class="hakkimizda">
<h1 data-aos="fade-up">Hakkımızda</h1>
<div class="hakkimizda-flex">
<div class="hakkimizda-metin" data-aos="fade-right">
<p>Marmara'nın kalbinde eşsiz bir keşif olan Nova Hotel, nazik hizmete ve sıcak misafirperverliğe değer verenler için eşi benzeri olmayan bir destinasyondur. Nova Hotel, İstanbul'un dışında ama bir o kadar da yakın ideal konumuyla iş dünyasına benzersiz bir konaklama deneyimi sunuyor.</p>
<p>Şehrin koşuşturmacasının dışında, hayatın çok daha kolaylaştığı özel bir ada var. Demokrasi ve Özgürlükler Adası'nda yer alan Nova Hotel'de İstanbul'un silüetinin ve Marmara Denizi'ne bakan Prens Adaları'nın nefes kesen manzarasının keyfine varacaksınız.</p>
<p>Yoğun yaşamdan kaçışınızda adanın harikaları sizi bekliyor. Nova Hotel'in ilham verici atmosferinde, hayatı farklı bir şekilde değerlendireceksiniz.</p>
<p><b>Adanın narin güzelliğiyle tanışın.</b></p>
</div>
<div class="hakkimizda-gorsel" data-aos="fade-left">
<img src="images/2.jpeg" alt="Nova Hotel İç Mekan">
</div>
</div>
</div>

<section id="odalar" class="odalar-onizleme" data-aos="fade-up">
<h2 class="odalar-onizleme-baslik">Odalarımız</h2>
<p class="odalar-onizleme-intro">Konforlu odalarımız, ada manzarası ve düşünceli detaylarla her konaklamayı özel kılıyor.</p>
<div id="odaKartlari" class="oda-kartlari" aria-live="polite"></div>
<p class="odalar-onizleme-alt"><a href="odalar.php">Tüm odaları ve detayları inceleyin →</a></p>
</section>

<section id="restaurant" class="restoran-teaser" data-aos="fade-up">
<h2>Restoran</h2>
<p>Yerel ve uluslararası lezzetler, taze deniz ürünleri ve şefimizin özel tarifleri tek adreste. Kahvaltıdan akşam yemeğine zarif bir atmosfer sizi bekliyor.</p>
<a class="restoran-teaser-link" href="restaurant.php">Menüyü ve restoranı keşfet</a>
</section>

<section class="degerler-bolum" id="degerlerimiz">
<h2 class="degerler-bolum-baslik" data-aos="fade-up">DEĞERLERİMİZ</h2>
<div class="degerler">
<div class="kart" data-aos="fade-up">
<h3 class="deger-kart-baslik">Yakın <span class="altin">X</span> Uzak</h3>
<p class="deger-kart-metin">İdeal konumuyla Nova Hotel mahremiyeti en üst düzeyde sağlarken şehre olan yakınlığıyla da çeşitli fırsatlar sunuyor.</p>
</div>
<div class="kart" data-aos="fade-up">
<h3 class="deger-kart-baslik">Gündoğumu <span class="altin">X</span> Günbatımı</h3>
<p class="deger-kart-metin">Yeni umutları beraberinde getiren gündoğumu ve güneşin denize dokunduğu günbatımı Nova Hotel'in özel atmosferinde farklı bir anlam kazanıyor.</p>
</div>
<div class="kart" data-aos="fade-up">
<h3 class="deger-kart-baslik">Odak <span class="altin">X</span> Rahatlama</h3>
<p class="deger-kart-metin">Nova Hotel sakin atmosferiyle iş için gelen misafirlerine odaklanma, küçük bir kaçış için gelen misafirlerine rahatlama şansı tanıyor.</p>
</div>
<div class="kart" data-aos="fade-up">
<h3 class="deger-kart-baslik">Kaçış <span class="altin">X</span> Eğlence</h3>
<p class="deger-kart-metin">Hayatın koşuşturmasından bir kaçış arayanlar için sakinliğin huzurlu kollarını açan Nova Hotel ada içerisinde sunduğu deneyimlerle eğlenceyi de bir arada yaşatıyor.</p>
</div>
</div>
</section>

<div class="hizmetlerimiz">
<h2 data-aos="fade-up">Hizmetlerimiz</h2>
<div class="hizmetler-grid">
<div class="hizmet-item" data-aos="zoom-in"><div class="hizmet-ikon"><i class="fa-solid fa-spa"></i></div><h4>Spa & Wellness</h4></div>
<div class="hizmet-item" data-aos="zoom-in"><div class="hizmet-ikon"><i class="fa-solid fa-building"></i></div><h4>Toplantı Salonu</h4></div>
<div class="hizmet-item" data-aos="zoom-in"><div class="hizmet-ikon"><i class="fa-solid fa-utensils"></i></div><h4>Restoran</h4></div>
<div class="hizmet-item" data-aos="zoom-in"><div class="hizmet-ikon"><i class="fa-solid fa-plane"></i></div><h4>Havalimanı Transferi</h4></div>
</div>
</div>

<div id="galeri" class="galeri">
<h1 data-aos="fade-up">Galeri</h1>
<div id="galeriGrid" class="galeri-grid"></div>
<p data-aos="fade-up" style="margin-top:32px;"><a href="galeri.php" style="color:#c5a059;font-weight:600;">Tüm galeriyi görüntüle →</a></p>
</div>

<div id="yorumlar" class="yorumlar">
<h2 data-aos="fade-up">Misafir Yorumları</h2>
<div id="yorumSwiper" class="swiper">
<div class="swiper-wrapper"></div>
<div class="swiper-pagination"></div>
<div class="swiper-button-prev"></div>
<div class="swiper-button-next"></div>
</div>
</div>

<?php $footer_iletisim = true; include __DIR__ . '/footer.php'; ?>

<div id="buyukResim" onclick="kapat()">
<div class="lightbox-icerik" onclick="event.stopPropagation()">
<button class="lightbox-ok sol" onclick="galeriOnceki(event)"><i class="fa-solid fa-chevron-left"></i></button>
<img id="buyukImg" alt="Büyük Görsel">
<button class="lightbox-ok sag" onclick="galeriSonraki(event)"><i class="fa-solid fa-chevron-right"></i></button>
</div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
