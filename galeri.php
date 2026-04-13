<?php require_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Galeri — Nova Hotel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="alt-page">

<?php include __DIR__ . '/navbar.php'; ?>

<section class="page-banner">
<h1>Galeri</h1>
<p>Otelimizden, manzaradan ve anlardan seçilmiş kareler.</p>
</section>

<div class="galeri" style="max-width:1100px;">
<div id="galeriGrid" class="galeri-grid"></div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<div id="buyukResim" onclick="kapat()">
<div class="lightbox-icerik" onclick="event.stopPropagation()">
<button class="lightbox-ok sol" onclick="galeriOnceki(event)"><i class="fa-solid fa-chevron-left"></i></button>
<img id="buyukImg" alt="Büyük Görsel">
<button class="lightbox-ok sag" onclick="galeriSonraki(event)"><i class="fa-solid fa-chevron-right"></i></button>
</div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="script.js"></script>
</body>
</html>
