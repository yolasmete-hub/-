# wfp — Joomla Site Teması

`joomla/templates/wfp/` altında, tasarım kaynağı olarak
[workingfamilies.org](https://workingfamilies.org/) (Working Families Party)
alınarak sıfırdan yazılmış bir Joomla 6 site teması.

> Not: Tema, gerçek sitenin stil dosyasından (WordPress "ms-starter" teması)
> alınan tasarım token'ları birebir kullanılarak yazıldı. Logo ve içerik
> dahil hiçbir telifli varlık kopyalanmadı; işaretleme ve CSS sıfırdan,
> Joomla'ya göre yazıldı.

## Tasarım dili (gerçek site token'ları)

| Öğe | Değer |
|---|---|
| Mor | `#481f82` (ana sayfa içerik bandı, buton gölgeleri) |
| Turuncu-kırmızı | `#f3563c` (hero ve CTA bandı, başlık alt çizgileri, tarihler) |
| Avokado sarısı | `#eaf96e` (butonlar, hover'lar, mor zeminde başlıklar) |
| Zemin | Bej `#e9e7df`; header ve footer siyah `#000` |
| Bağlantılar | `#1d4dc7` (içerikte `#461f83`), hover `#2f80ed`/turuncu |
| Başlık fontu | Barlow Condensed (gerçek sitedeki ticari `pf_venue_condensed` fontunun açık lisanslı muadili) |
| Metin fontu | Helvetica Neue yığını (yedek: gömülü Archivo) |

İmza desenler: kayık blok gölgeli butonlar (5px ofsetli `::before`),
başlıklarda 3 piksel turuncu alt çizgi, kart tarihlerinde kondanse büyük
harf turuncu, dava kartlarında sarı/siyah/turuncu ardışık zemin dönüşümü.

Fontlar `@fontsource` npm paketlerinden alınıp temaya gömüldü (OFL lisansı,
`latin` + Türkçe karakterler için `latin-ext` setleri). Dış CDN bağımlılığı yok.

## Tema yapısı

```
joomla/templates/wfp/
├── templateDetails.xml   # manifest: pozisyonlar + tema parametreleri
├── index.php             # ana şablon (header, hero, bölümler, footer)
├── component.php         # modal/yazdırma görünümü
├── error.php             # 404/500 sayfası
├── offline.php           # bakım modu sayfası
├── css/template.css      # tüm stiller (~tek dosya, değişkenlerle)
├── js/template.js        # mobil menü aç/kapa
├── fonts/                # woff2 dosyaları
├── images/favicon.svg
├── html/layouts/chromes/ # wfpsection, wfpcard, wfpfooter modül krom stilleri
└── language/en-GB/       # tpl_wfp.ini dil dosyaları
```

### Modül pozisyonları

`topbar`, `below-top`, `menu`, `search`, `hero`, `top-a`, `top-b`,
`main-top`, `main-bottom`, `breadcrumbs`, `sidebar-right`, `cta`,
`footer-a/b/c`, `copyright`, `debug`

### Tema parametreleri (Site → Templates → Styles → WFP)

Slogan, Donate/Join buton metni ve adresi, dört sosyal medya adresi,
footer tanıtım metni.

## Kurulum (yeni bir ortamda)

1. Tema dosyaları `joomla/templates/wfp/` altında; keşfet ve kur:
   ```bash
   php cli/joomla.php extension:discover
   php cli/joomla.php extension:discover:install --eid <wfp-eid>
   ```
2. Varsayılan yap: Administrator → System → Templates → Styles → WFP → Default
   (veya `jos_template_styles` tablosunda `home=1`).
3. Ana menü modülünü `menu` pozisyonuna taşı.

## Yönetim rehberi — hangi alan nereden düzenlenir?

Yönetici paneli: `http://127.0.0.1:8080/administrator/` (kullanıcı `admin`).
Her ana sayfa bölgesi Joomla'nın kendi araçlarıyla yönetilir:

| Alan | Nereden düzenlenir | Nasıl çalışır |
|---|---|---|
| **Üst şerit (küçük menü)** | Menus → **Tertiary Nav** | `topbar` pozisyonundaki **Header Tertiary** modülü (About, Get Active, Store, Sign Up); aynı menü footer'da da kullanılır |
| **Üst menü** | Menus → **Main Menu** (öğeler) + System → Site Modules → **Main Menu** | `menu` pozisyonundaki menü modülü (Our 2026 Candidates, Latest News, Events, Working Families Guarantee) |
| **Manşet** | System → Site Modules → **Manşet (Hero)** | `hero` pozisyonunda özel HTML; gerçek başlık metni, `<strong>` = siyah bölüm |
| **Get Active** | Menus → **Get Active** (öğeler) + Site Modules → **Get Active** | `below-top` pozisyonu, manşetin yanındaki mor kutu; gerçek 5 öğe (Become a WFP Member, Make your plan to vote, Join a Welcome Gathering, Volunteer with WFP, Apply for our endorsement) |
| **Dava kartları** | System → Site Modules → **Our fights** | `top-a` pozisyonunda özel HTML; kart zeminleri otomatik sarı/siyah/turuncu döner |
| **Latest** | Content → Categories → **Latest** kategorisine makale ekle | `main-top` pozisyonundaki **Latest** modülü (mod_articles, `wfpcards`) son 3 yazıyı çeker; altındaki "View All News" + SMS CTA'sı **Latest Extras** modülünde |
| **Siyah bant** | System → Site Modules → **Feature (Siyah Bant)** | `main-bottom` pozisyonunda özel HTML; gerçek metin + Who We Are / Membership / Our Candidates linkleri |
| **Contribute** | System → Site Modules → **Contribute** | `cta` pozisyonundaki turuncu bant; gerçek metin + $10/$27/$100/$250/Other Amount butonları |
| **Footer büyük menü** | Menus → **Footer Menu** | `footer-a`; Sign Up ve Donate öğeleri "Link CSS Style: wfp-underline" ile sarı alt çizgili |
| **Footer küçük-büyük-harf satır** | Menus → **Tertiary Nav** | `footer-b` pozisyonundaki **Footer Tertiary** modülü |
| **Footer alt küçük menü** | Menus → **Footer Legal** | `footer-d` pozisyonundaki **Footer Small Menu** (Jobs, Media Center, Public Filings, Media Inquiries, Contact, Privacy Policy) |
| **Footer posta adresi + Made with** | System → Site Modules → **Footer Contact** | `footer-c` pozisyonunda özel HTML |
| **Disclaimer + Paid for** | System → Site Modules → **Footer Disclaimer** | `copyright` pozisyonu; `wfp-paid-for` sınıflı div çerçeveli kutuyu üretir |
| **Sosyal ikonlar, Contribute/Sign Up butonları, slogan** | System → Site Template Styles → **WFP - Default** → Advanced | Tema parametreleri |

Get Active, Latest, Latest Extras ve Siyah Bant modülleri yalnızca ana sayfaya
atanmıştır (Menu Assignment: Home). Footer ve üst şerit tüm sayfalarda görünür.
Eski About / Our Fights / Take Action sayfaları menüden kalktı ama linkleri
kırılmasın diye **Hidden Pages** menüsünde yaşamaya devam ediyor.

## Demo içerik

`docs/wfp-demo/` altında:

- `seed.php` — makaleler (3 haber + Hakkımızda + Harekete Geç) ve ana menü
  öğelerini oluşturur. Joomla kökünden çalıştırılır: `php seed.php`
- `seed_modules.php` — hero, dava kartları (6 adet), CTA bandı modüllerini oluşturur.
- `seed_structure.php` — yönetilebilir bölge yapısını kurar: Latest
  kategorisi, Get Active / Footer Menu / Footer Legal menüleri ve öğeleri,
  Latest News (mod_articles), Feature, footer modülleri.
- `seed_real_content.php` — gerçek ana sayfa içeriğine hizalar: gerçek menüler,
  Get Active öğeleri, manşet/feature/Contribute metinleri, footer içerikleri.
- `joomla_db.sql` — bu demonun eksiksiz veritabanı dökümü (test ortamı;
  admin parolası KURULUM.md'dekiyle aynıdır).

## Doğrulama görüntüleri

| Sayfa | Dosya |
|---|---|
| Ana sayfa (tam) | ![Ana sayfa](docs/theme-home-full.png) |
| Makale sayfası | ![Makale](docs/theme-article.png) |
| Haber listesi | ![Haberler](docs/theme-news.png) |
| Mobil menü | ![Mobil](docs/theme-mobile.png) |
