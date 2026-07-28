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
| **Üst menü** | Menus → **Main Menu** (öğeler) + System → Site Modules → **Main Menu** | `menu` pozisyonundaki menü modülü; öğe ekle/sil/sırala → header'a yansır |
| **Manşet** | System → Site Modules → **Manşet (Hero)** | `hero` pozisyonunda özel HTML modülü; başlık/metin/butonlar burada (`<strong>` = siyah vurgu) |
| **Get Active** | Menus → **Get Active** (öğeler) + Site Modules → **Get Active** | `below-top` pozisyonu, manşetin yanındaki mor kutu; her öğe bir sayfaya gider |
| **Dava kartları** | System → Site Modules → **Our fights** | `top-a` pozisyonunda özel HTML; kart zeminleri otomatik sarı/siyah/turuncu döner |
| **Latest** | Content → Categories → **Latest** kategorisine makale ekle | `main-top` pozisyonundaki **Latest News** modülü (mod_articles, `wfpcards` yerleşimi) bu kategorideki son 3 yazıyı otomatik çeker; sayı/sıralama modül ayarından |
| **Siyah bant** | System → Site Modules → **Feature (Siyah Bant)** | `main-bottom` pozisyonunda özel HTML (`wfp-feature-sub` + `h2` + `p` + linkler) |
| **Contribute** | System → Site Modules → **Contribute** | `cta` pozisyonundaki turuncu bant; sabit özel HTML — metni burada değiştirirsin |
| **Footer büyük menü** | Menus → **Footer Menu** | `footer-a` pozisyonundaki menü modülü |
| **Footer küçük menü** | Menus → **Footer Legal** | `footer-b` pozisyonu (Privacy policy, Contact) |
| **Footer iletişim** | System → Site Modules → **Footer Contact** | `footer-c` pozisyonunda özel HTML |
| **Disclaimer + Paid for** | System → Site Modules → **Footer Disclaimer** | `copyright` pozisyonu; `wfp-paid-for` sınıflı div çerçeveli kutuyu üretir |
| **Sosyal ikonlar, Donate/Join butonları, slogan** | System → Site Template Styles → **WFP - Default** → Advanced | Tema parametreleri |

Get Active, Latest ve Siyah Bant modülleri yalnızca ana sayfaya atanmıştır
(Menu Assignment: Home). Footer modülleri tüm sayfalarda görünür.

## Demo içerik

`docs/wfp-demo/` altında:

- `seed.php` — makaleler (3 haber + Hakkımızda + Harekete Geç) ve ana menü
  öğelerini oluşturur. Joomla kökünden çalıştırılır: `php seed.php`
- `seed_modules.php` — hero, dava kartları (6 adet), CTA bandı modüllerini oluşturur.
- `seed_structure.php` — yönetilebilir bölge yapısını kurar: Latest
  kategorisi, Get Active / Footer Menu / Footer Legal menüleri ve öğeleri,
  Latest News (mod_articles), Feature, footer modülleri.
- `joomla_db.sql` — bu demonun eksiksiz veritabanı dökümü (test ortamı;
  admin parolası KURULUM.md'dekiyle aynıdır).

## Doğrulama görüntüleri

| Sayfa | Dosya |
|---|---|
| Ana sayfa (tam) | ![Ana sayfa](docs/theme-home-full.png) |
| Makale sayfası | ![Makale](docs/theme-article.png) |
| Haber listesi | ![Haberler](docs/theme-news.png) |
| Mobil menü | ![Mobil](docs/theme-mobile.png) |
