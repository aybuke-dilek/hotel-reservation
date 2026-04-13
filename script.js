/* ========== NOVA HOTEL - Ana Script ========== */

var siteData = { odalar: [], yorumlar: [], galeri: [] };
var galeriIndex = 0;

/* ===== FETCH API - data.json'dan veri çek ===== */
function veriYukle() {
    return fetch('data.json')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            siteData = data;
            return data;
        })
        .catch(function(err) {
            console.warn('data.json yüklenemedi, varsayılan veri kullanılıyor.', err);
            siteData = {
                odalar: [
                    { id: 1, adi: "Standart Oda", gorsel: "images/1.jpeg", kisi: 2, metrekare: 22, yatak: "Çift Kişilik", wifi: true, klima: true, aciklama: "Ada manzaralı standart oda." },
                    { id: 2, adi: "Deluxe Oda", gorsel: "images/2.jpeg", kisi: 3, metrekare: 32, yatak: "King Yatak", wifi: true, klima: true, aciklama: "Geniş ve ferah deluxe oda." },
                    { id: 3, adi: "Suite", gorsel: "images/3.jpeg", kisi: 4, metrekare: 48, yatak: "Ayrı Oturma", wifi: true, klima: true, aciklama: "Ayrı oturma alanı olan suite." }
                ],
                yorumlar: [
                    { isim: "Ayşe & Mehmet Y.", yorum: "Ada havasının huzuru ve Nova Hotel'in misafirperverliği...", point: 5 }
                ],
                galeri: [
                    { src: "images/1.jpeg", aciklama: "Deluxe Oda" },
                    { src: "images/2.jpeg", aciklama: "İç Mekan" },
                    { src: "images/3.jpeg", aciklama: "Spa" },
                    { src: "images/4.jpeg", aciklama: "Restaurant" },
                    { src: "images/5.jpeg", aciklama: "Manzara" },
                    { src: "images/6.jpeg", aciklama: "Havuz" }
                ]
            };
            return siteData;
        });
}

/* ===== Yıldız oluştur (puan 1-5) ===== */
function yildizOlustur(point) {
    var html = '';
    for (var i = 0; i < 5; i++) {
        html += i < point ? '<span class="yildiz-dolu">★</span>' : '<span class="yildiz-bos">★</span>';
    }
    return html;
}

/* ===== Odaları DOM'a yaz ===== */
function odalariYukle() {
    var container = document.getElementById('odaKartlari');
    if (!container) return;
    var html = '';
    siteData.odalar.forEach(function(o) {
        html += '<div class="oda-kart" data-aos="fade-up">';
        html += '<div class="oda-kart-gorsel"><img src="' + o.gorsel + '" alt="' + o.adi + '"></div>';
        html += '<div class="oda-kart-icerik">';
        html += '<h3>' + o.adi + '</h3>';
        html += '<div class="oda-ozellik"><i class="oda-ikon fa-solid fa-user"></i><span>' + o.kisi + ' Kişi</span></div>';
        html += '<div class="oda-ozellik"><i class="oda-ikon fa-solid fa-ruler-combined"></i><span>' + o.metrekare + ' m²</span></div>';
        html += '<div class="oda-ozellik"><i class="oda-ikon fa-solid fa-bed"></i><span>' + o.yatak + '</span></div>';
        html += '<a href="odalar.php" class="oda-kart-buton">Detayları Gör</a>';
        html += '</div></div>';
    });
    container.innerHTML = html;
}

function odaOzellikSatir(ikon, metin) {
    return '<div class="oda-detay-oz"><i class="fa-solid ' + ikon + '"></i><span>' + metin + '</span></div>';
}

/* ===== Odalar sayfası — detaylı kartlar ===== */
function odalariDetayYukle() {
    var container = document.getElementById('odaDetayListe');
    if (!container) return;
    var html = '';
    siteData.odalar.forEach(function(o) {
        var wifi = o.wifi !== false;
        var klima = o.klima !== false;
        var aciklama = o.aciklama || 'Konforlu ve özenle döşenmiş odamızda unutulmaz bir konaklama sizi bekliyor.';
        html += '<article class="oda-detay-kart" data-aos="fade-up">';
        html += '<div class="oda-detay-gorsel"><img src="' + o.gorsel + '" alt="' + o.adi + '"></div>';
        html += '<div class="oda-detay-icerik">';
        html += '<h2>' + o.adi + '</h2>';
        html += '<p class="oda-detay-aciklama">' + aciklama + '</p>';
        html += '<div class="oda-detay-ozellikler">';
        html += odaOzellikSatir('fa-ruler-combined', o.metrekare + ' m²');
        html += odaOzellikSatir('fa-bed', o.yatak);
        html += odaOzellikSatir('fa-user', o.kisi + ' kişiye kadar');
        html += odaOzellikSatir('fa-wifi', wifi ? 'Ücretsiz Wi‑Fi' : 'Wi‑Fi bilgi için resepsiyon');
        html += odaOzellikSatir('fa-snowflake', klima ? 'Klima' : 'Klima talep üzerine');
        html += odaOzellikSatir('fa-mug-hot', 'Nespresso / çay seti');
        html += odaOzellikSatir('fa-tv', 'Akıllı TV');
        html += '</div>';
        html += '<a href="rezervasyon.php" class="oda-detay-rez">Rezervasyon Yap</a>';
        html += '</div></article>';
    });
    container.innerHTML = html;
}

function initRestaurantMenuTabs() {
    var bar = document.querySelector('.menu-sekmeler');
    if (!bar) return;
    var buttons = bar.querySelectorAll('button');
    var panels = document.querySelectorAll('.menu-panel');
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            buttons.forEach(function(b) {
                b.classList.remove('aktif');
                b.setAttribute('aria-selected', 'false');
            });
            panels.forEach(function(p) { p.classList.remove('aktif'); });
            btn.classList.add('aktif');
            btn.setAttribute('aria-selected', 'true');
            var id = btn.getAttribute('data-menu');
            var panel = id ? document.getElementById(id) : null;
            if (panel) panel.classList.add('aktif');
        });
    });
}

/* ===== Galeriyi DOM'a yaz ===== */
function galeriyiYukle() {
    var container = document.getElementById('galeriGrid');
    if (!container) return;
    var html = '';
    siteData.galeri.forEach(function(g, i) {
        html += '<div class="galeri-item" data-aos="zoom-in">';
        html += '<div class="galeri-resim-wrap">';
        html += '<img src="' + g.src + '" alt="' + g.aciklama + '" onclick="buyut(' + i + ')">';
        html += '<div class="galeri-overlay" onclick="buyut(' + i + ')">';
        html += '<i class="galeri-buyut fa-solid fa-magnifying-glass"></i><span>Resmi Gör</span>';
        html += '</div></div>';
        html += '<span class="galeri-aciklama">' + g.aciklama + '</span></div>';
    });
    container.innerHTML = html;
}

/* ===== Swiper - Yorumlar slider ===== */
function yorumSwiperBaslat() {
    var wrapper = document.querySelector('#yorumSwiper .swiper-wrapper');
    if (!wrapper) return;
    var html = '';
    siteData.yorumlar.forEach(function(y) {
        html += '<div class="swiper-slide">';
        html += '<div class="yorum-slide">';
        html += '<div class="yorum-yildizlar">' + yildizOlustur(y.point) + '</div>';
        html += '<blockquote>' + y.yorum + '</blockquote>';
        html += '<p class="yorum-yazar">— ' + y.isim + '</p>';
        html += '</div></div>';
    });
    wrapper.innerHTML = html;
    new Swiper('#yorumSwiper', {
        loop: true,
        autoplay: { delay: 5000 },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        }
    });
}

/* ===== Galeri Lightbox ===== */
function buyut(index) {
    galeriIndex = index;
    var src = siteData.galeri[galeriIndex].src;
    document.getElementById('buyukResim').style.display = 'flex';
    document.getElementById('buyukImg').src = src;
}

function galeriOnceki(e) {
    e.stopPropagation();
    galeriIndex = (galeriIndex - 1 + siteData.galeri.length) % siteData.galeri.length;
    document.getElementById('buyukImg').src = siteData.galeri[galeriIndex].src;
}

function galeriSonraki(e) {
    e.stopPropagation();
    galeriIndex = (galeriIndex + 1) % siteData.galeri.length;
    document.getElementById('buyukImg').src = siteData.galeri[galeriIndex].src;
}

function kapat() {
    document.getElementById('buyukResim').style.display = 'none';
}

/* ===== Logo: ana sayfada yumuşak kaydırma ===== */
function anasayfaMi() {
    var path = (window.location.pathname || '').replace(/\\/g, '/');
    return path === '/' || /index\.php$/i.test(path) || /index\.html$/i.test(path);
}

function logoHomeBagla() {
    document.querySelectorAll('a.logo-home').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!anasayfaMi()) return;
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
}

/* ===== Header scroll ===== */
window.addEventListener('scroll', function() {
    var header = document.querySelector('header');
    if (!header) return;
    if (document.body.classList.contains('alt-page')) {
        header.classList.add('scrolled');
        return;
    }
    if (window.scrollY > 80) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
});

/* ===== Init ===== */
document.addEventListener('DOMContentLoaded', function() {
    logoHomeBagla();
    if (document.body.classList.contains('alt-page')) {
        var h = document.querySelector('header');
        if (h) h.classList.add('scrolled');
    }
    veriYukle().then(function() {
        odalariYukle();
        odalariDetayYukle();
        galeriyiYukle();
        yorumSwiperBaslat();
        initRestaurantMenuTabs();
        AOS.init({ duration: 800, offset: 50, once: true });
    });
});

/* Rezervasyon sayfası (önceden rezervasyon.js) */
(function () {
    var KAT_SAYISI = 10;
    var ODA_KAT_BASINA = 300;

    var MANZARA_ETIKET = { kara: 'Kara / Şehir', deniz: 'Deniz', havuz: 'Havuz' };
    var YATAK_ETIKET = { king: 'Tek büyük yatak (King)', twin: 'İki ayrı yatak (Twin)' };

    var odaMeta = {
        standart: {
            key: 'standart',
            adi: 'Standart Oda',
            gorsel: 'images/1.jpeg',
            baseFiyat: 3200,
            m2: 22,
            kapasite: 2,
            yatakOz: 'Çift kişilik / King uyumlu',
            aciklama: 'Ada manzaralı standart oda; konforlu ve ferah.'
        },
        deluxe: {
            key: 'deluxe',
            adi: 'Deluxe Oda',
            gorsel: 'images/2.jpeg',
            baseFiyat: 4800,
            m2: 32,
            kapasite: 3,
            yatakOz: 'King veya Twin talebe göre',
            aciklama: 'Geniş deluxe oda; premium banyo ve oturma alanı.'
        },
        suite: {
            key: 'suite',
            adi: 'Suite',
            gorsel: 'images/3.jpeg',
            baseFiyat: 7200,
            m2: 48,
            kapasite: 4,
            yatakOz: 'Ayrı yatak odası + oturma',
            aciklama: 'Ayrı oturma alanı ve geniş suite deneyimi.'
        }
    };

    function odaNumaralariKatIcin(kat) {
        var k = parseInt(kat, 10);
        if (k < 1 || k > KAT_SAYISI) return { bas: 0, bit: 0 };
        var bas = (k - 1) * 100 + 101;
        var bit = bas + ODA_KAT_BASINA - 1;
        return { bas: bas, bit: bit };
    }

    function katSelectDoldur(selectEl) {
        if (!selectEl) return;
        var html = '';
        for (var f = 1; f <= KAT_SAYISI; f++) {
            html += '<option value="' + f + '">' + f + '. kat</option>';
        }
        selectEl.innerHTML = html;
    }

    function odaNoSelectDoldur(selectEl, kat) {
        if (!selectEl) return;
        var ar = odaNumaralariKatIcin(kat);
        var html = '';
        for (var n = ar.bas; n <= ar.bit; n++) {
            html += '<option value="' + n + '">Oda ' + n + '</option>';
        }
        selectEl.innerHTML = html;
    }

    function geceSayisi(girisStr, cikisStr) {
        if (!girisStr || !cikisStr) return 0;
        var g = new Date(girisStr + 'T12:00:00');
        var c = new Date(cikisStr + 'T12:00:00');
        var ms = c - g;
        var gun = Math.ceil(ms / (1000 * 60 * 60 * 24));
        return gun > 0 ? gun : 0;
    }

    function gecelikFiyat(odaTip, manzara) {
        var meta = odaMeta[odaTip] || odaMeta.standart;
        var fiyat = meta.baseFiyat;
        if (manzara === 'deniz') fiyat = Math.round(fiyat * 1.2);
        return fiyat;
    }

    function paraFormat(n) {
        return new Intl.NumberFormat('tr-TR', { maximumFractionDigits: 0 }).format(n) + ' ₺';
    }

    var app = document.getElementById('rezervasyonApp');
    if (!app) return;

    var modalRoot = document.getElementById('rezModal');
    var modalMesaj = document.getElementById('rezModalMesaj');
    var modalBaslik = document.getElementById('rezModalBaslik');
    var modalIkon = document.getElementById('rezModalIkon');
    var modalKapatBtn = document.getElementById('rezModalKapat');
    var modalBackdrop = modalRoot ? modalRoot.querySelector('.rez-modal__backdrop') : null;
    var sonOdak = null;

    function rezModalGoster(metin, tur) {
        tur = tur || 'uyari';
        if (!modalRoot || !modalMesaj || !modalBaslik || !modalIkon) {
            window.alert(metin);
            return;
        }
        sonOdak = document.activeElement;
        modalMesaj.textContent = metin;
        modalRoot.classList.toggle('rez-modal--basari', tur === 'basari');
        if (tur === 'basari') {
            modalBaslik.textContent = 'Talebiniz alındı';
            modalIkon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        } else {
            modalBaslik.textContent = 'Eksik veya hatalı bilgi';
            modalIkon.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i>';
        }
        modalRoot.classList.add('rez-modal--acik');
        modalRoot.removeAttribute('hidden');
        modalRoot.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (modalKapatBtn) modalKapatBtn.focus();
    }

    function rezModalKapat() {
        if (!modalRoot) return;
        modalRoot.classList.remove('rez-modal--acik');
        modalRoot.setAttribute('aria-hidden', 'true');
        modalRoot.setAttribute('hidden', '');
        document.body.style.overflow = '';
        if (sonOdak && typeof sonOdak.focus === 'function') {
            try {
                sonOdak.focus();
            } catch (e) {}
        }
        sonOdak = null;
    }

    if (modalKapatBtn) modalKapatBtn.addEventListener('click', rezModalKapat);
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', rezModalKapat);
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalRoot && modalRoot.classList.contains('rez-modal--acik')) {
            rezModalKapat();
        }
    });

    var el = {
        giris: document.getElementById('giris'),
        cikis: document.getElementById('cikis'),
        yetiskin: document.getElementById('yetiskin'),
        cocuk: document.getElementById('cocuk'),
        odaTip: document.getElementById('odaTip'),
        kat: document.getElementById('kat'),
        odaNo: document.getElementById('odaNo'),
        manzara: document.getElementById('manzara'),
        yatakTip: document.getElementById('yatakTip'),
        ekJakuzi: document.getElementById('ekJakuzi'),
        ekBalkon: document.getElementById('ekBalkon'),
        ekBebek: document.getElementById('ekBebek'),
        form: document.getElementById('rezForm'),
        ozetTarih: document.getElementById('ozetTarih'),
        ozetGece: document.getElementById('ozetGece'),
        ozetMisafir: document.getElementById('ozetMisafir'),
        ozetOdaTip: document.getElementById('ozetOdaTip'),
        ozetKatOda: document.getElementById('ozetKatOda'),
        ozetManzara: document.getElementById('ozetManzara'),
        ozetYatak: document.getElementById('ozetYatak'),
        ozetEkstra: document.getElementById('ozetEkstra'),
        ozetToplam: document.getElementById('ozetToplam'),
        ozetGecelik: document.getElementById('ozetGecelik'),
        odaAralikYardim: document.getElementById('odaAralikYardim'),
        rezRoomImg: document.getElementById('rezRoomImg'),
        rezRoomTitle: document.getElementById('rezRoomTitle'),
        rezRoomSpecs: document.getElementById('rezRoomSpecs'),
        rezRoomDesc: document.getElementById('rezRoomDesc')
    };

    var currentStep = 1;

    function setMinDates() {
        var bugun = new Date();
        bugun.setHours(0, 0, 0, 0);
        var iso = bugun.toISOString().slice(0, 10);
        if (el.giris) el.giris.min = iso;
        if (el.cikis) {
            if (el.giris && el.giris.value) {
                var g = new Date(el.giris.value + 'T12:00:00');
                g.setDate(g.getDate() + 1);
                el.cikis.min = g.toISOString().slice(0, 10);
            } else el.cikis.min = iso;
        }
    }

    function showStep(n) {
        currentStep = n;
        document.querySelectorAll('.rez-adim').forEach(function (btn) {
            var s = parseInt(btn.getAttribute('data-step'), 10);
            var on = s === n;
            btn.classList.toggle('aktif', on);
            btn.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        document.querySelectorAll('[data-step-panel]').forEach(function (panel) {
            var s = parseInt(panel.getAttribute('data-step-panel'), 10);
            var on = s === n;
            panel.classList.toggle('gizle', !on);
            panel.hidden = !on;
        });
    }

    function odaPaneliGuncelle() {
        var tip = el.odaTip ? el.odaTip.value : 'standart';
        var meta = odaMeta[tip] || odaMeta.standart;
        if (el.rezRoomImg) {
            el.rezRoomImg.src = meta.gorsel;
            el.rezRoomImg.alt = meta.adi;
        }
        if (el.rezRoomTitle) el.rezRoomTitle.textContent = meta.adi;
        if (el.rezRoomDesc) el.rezRoomDesc.textContent = meta.aciklama;
        if (el.rezRoomSpecs) {
            el.rezRoomSpecs.innerHTML =
                '<li><i class="fa-solid fa-ruler-combined"></i> <span>' + meta.m2 + ' m²</span></li>' +
                '<li><i class="fa-solid fa-user"></i> <span>' + meta.kapasite + ' kişiye kadar</span></li>' +
                '<li><i class="fa-solid fa-bed"></i> <span>' + meta.yatakOz + '</span></li>';
        }
    }

    function ekstraMetin() {
        var parcalar = [];
        if (el.ekJakuzi && el.ekJakuzi.checked) parcalar.push('Jakuzi');
        if (el.ekBalkon && el.ekBalkon.checked) parcalar.push('Balkon');
        if (el.ekBebek && el.ekBebek.checked) parcalar.push('Bebek yatağı');
        var sig = document.querySelector('input[name="sigara"]:checked');
        if (sig) parcalar.push(sig.value === 'icilebilir' ? 'Sigara içilebilir' : 'Sigara içilmez');
        return parcalar.length ? parcalar.join(' · ') : '—';
    }

    function ozetiGuncelle() {
        var g = el.giris && el.giris.value;
        var c = el.cikis && el.cikis.value;
        var gece = geceSayisi(g, c);

        if (el.ozetTarih) {
            el.ozetTarih.textContent = g && c ? g + ' → ' + c : '—';
        }
        if (el.ozetGece) {
            el.ozetGece.textContent = gece > 0 ? gece + ' gece' : '—';
        }
        var y = el.yetiskin ? parseInt(el.yetiskin.value, 10) || 0 : 0;
        var co = el.cocuk ? parseInt(el.cocuk.value, 10) || 0 : 0;
        if (el.ozetMisafir) {
            el.ozetMisafir.textContent = y || co ? y + ' yetişkin' + (co ? ', ' + co + ' çocuk' : '') : '—';
        }

        var tip = el.odaTip ? el.odaTip.value : 'standart';
        var meta = odaMeta[tip] || odaMeta.standart;
        if (el.ozetOdaTip) el.ozetOdaTip.textContent = meta.adi;

        var kat = el.kat ? el.kat.value : '';
        var no = el.odaNo ? el.odaNo.value : '';
        if (el.ozetKatOda) {
            if (kat && no) el.ozetKatOda.textContent = kat + '. kat · ' + no;
            else if (kat) el.ozetKatOda.textContent = kat + '. kat';
            else el.ozetKatOda.textContent = '—';
        }

        var man = el.manzara ? el.manzara.value : 'kara';
        if (el.ozetManzara) el.ozetManzara.textContent = MANZARA_ETIKET[man] || '—';

        var yt = el.yatakTip ? el.yatakTip.value : '';
        if (el.ozetYatak) el.ozetYatak.textContent = yt ? YATAK_ETIKET[yt] || yt : '—';

        if (el.ozetEkstra) el.ozetEkstra.textContent = ekstraMetin();

        var gecelik = gecelikFiyat(tip, man);
        var toplam = gece > 0 ? gecelik * gece : 0;
        if (el.ozetGecelik) {
            var ek = man === 'deniz' ? ' (deniz +%20)' : '';
            el.ozetGecelik.textContent = 'Gecelik: ' + paraFormat(gecelik) + ek;
        }
        if (el.ozetToplam) {
            el.ozetToplam.textContent = gece > 0 ? paraFormat(toplam) : '—';
        }
    }

    function katDegisti() {
        var kat = el.kat ? el.kat.value : '1';
        odaNoSelectDoldur(el.odaNo, kat);
        var ar = odaNumaralariKatIcin(kat);
        if (el.odaAralikYardim) {
            el.odaAralikYardim.textContent =
                kat + '. kat: oda numaraları ' + ar.bas + ' — ' + ar.bit + ' (toplam ' + ODA_KAT_BASINA + ' oda).';
        }
        ozetiGuncelle();
    }

    function validateStep(step) {
        if (step === 1) {
            if (!el.giris.value || !el.cikis.value) {
                rezModalGoster('Lütfen giriş ve çıkış tarihlerini seçin.', 'uyari');
                return false;
            }
            if (new Date(el.cikis.value) <= new Date(el.giris.value)) {
                rezModalGoster('Çıkış tarihi, giriş tarihinden sonra olmalıdır.', 'uyari');
                return false;
            }
            var y = parseInt(el.yetiskin.value, 10);
            if (!y || y < 1) {
                rezModalGoster('En az 1 yetişkin girilmelidir.', 'uyari');
                return false;
            }
        }
        if (step === 2) {
            if (!el.odaNo.value) {
                rezModalGoster('Lütfen oda numarası seçin.', 'uyari');
                return false;
            }
        }
        return true;
    }

    document.querySelectorAll('.rez-btn-ileri').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var next = parseInt(btn.getAttribute('data-next'), 10);
            if (!validateStep(currentStep)) return;
            showStep(next);
        });
    });

    document.querySelectorAll('.rez-btn-geri').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var prev = parseInt(btn.getAttribute('data-prev'), 10);
            showStep(prev);
        });
    });

    document.querySelectorAll('.rez-adim').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var hedef = parseInt(btn.getAttribute('data-step'), 10);
            if (hedef < currentStep) {
                showStep(hedef);
                return;
            }
            if (hedef === currentStep) return;
            if (hedef === 2 && !validateStep(1)) return;
            if (hedef === 3 && (!validateStep(1) || !validateStep(2))) return;
            showStep(hedef);
        });
    });

    if (el.form) {
        el.form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validateStep(1)) {
                showStep(1);
                return;
            }
            if (!validateStep(2)) {
                showStep(2);
                return;
            }
            rezModalGoster(
                'Teşekkürler! Rezervasyon talebiniz alındı (örnek simülasyon). Tahmini toplam: ' +
                    (el.ozetToplam ? el.ozetToplam.textContent : '') +
                    ' Resepsiyon en kısa sürede sizinle iletişime geçecektir.',
                'basari'
            );
        });
    }

    if (el.giris) {
        el.giris.addEventListener('change', function () {
            setMinDates();
            if (el.cikis && el.cikis.value && el.giris.value && el.cikis.value <= el.giris.value) {
                var g = new Date(el.giris.value + 'T12:00:00');
                g.setDate(g.getDate() + 1);
                el.cikis.value = g.toISOString().slice(0, 10);
            }
            ozetiGuncelle();
        });
    }

    if (el.cikis) {
        el.cikis.addEventListener('change', function () {
            if (el.giris && el.giris.value && el.cikis.value && el.cikis.value <= el.giris.value) {
                var g = new Date(el.giris.value + 'T12:00:00');
                g.setDate(g.getDate() + 1);
                el.cikis.value = g.toISOString().slice(0, 10);
            }
            ozetiGuncelle();
        });
    }

    [el.yetiskin, el.cocuk, el.kat, el.odaNo, el.manzara, el.yatakTip, el.ekJakuzi, el.ekBalkon, el.ekBebek].forEach(function (inp) {
        if (inp) inp.addEventListener('change', ozetiGuncelle);
    });
    document.querySelectorAll('input[name="sigara"]').forEach(function (r) {
        r.addEventListener('change', ozetiGuncelle);
    });

    if (el.kat) el.kat.addEventListener('change', katDegisti);
    if (el.odaTip) {
        el.odaTip.addEventListener('change', function () {
            odaPaneliGuncelle();
            ozetiGuncelle();
        });
    }

    function veridenOdaMetaCevir(odalar) {
        if (!odalar || !odalar.length) return;
        var map = { 'Standart Oda': 'standart', 'Deluxe Oda': 'deluxe', Suite: 'suite' };
        odalar.forEach(function (o) {
            var key = map[o.adi] || (o.adi && o.adi.toLowerCase().indexOf('deluxe') >= 0 ? 'deluxe' : o.adi && o.adi.toLowerCase().indexOf('suite') >= 0 ? 'suite' : null);
            if (!key || !odaMeta[key]) return;
            odaMeta[key].gorsel = o.gorsel || odaMeta[key].gorsel;
            odaMeta[key].adi = o.adi || odaMeta[key].adi;
            if (o.metrekare) odaMeta[key].m2 = o.metrekare;
            if (o.kisi) odaMeta[key].kapasite = o.kisi;
            if (o.yatak) odaMeta[key].yatakOz = o.yatak;
            if (o.aciklama) odaMeta[key].aciklama = o.aciklama;
        });
    }

    setMinDates();
    katSelectDoldur(el.kat);
    katDegisti();
    odaPaneliGuncelle();
    ozetiGuncelle();
    showStep(1);

    if (typeof fetch !== 'undefined') {
        fetch('data.json')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.odalar) veridenOdaMetaCevir(data.odalar);
                odaPaneliGuncelle();
                ozetiGuncelle();
            })
            .catch(function () {});
    }

    if (typeof AOS !== 'undefined') AOS.refresh();
})();
