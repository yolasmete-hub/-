# Solidarity — Joomla 5 Şablonu

**Solidarity**, aktivist/hareket tarzı siteler için sıfırdan yazılmış özgün bir Joomla 5 site şablonudur. Tasarım dili: krem zemin üzerinde derin mor + canlı turuncu palet, kalın–dar büyük harfli başlık tipografisi (Anton), bayrak motifi, dev başlıklı hero bandı, kayan şerit (ticker), sert gölgeli kartlar, lavanta e-bülten bandı ve koyu mor footer.

> Not: Bu şablon workingfamilies.org sitesinin tasarım dilini **birebir takip edecek şekilde** tamamen özgün olarak yazılmıştır. Sitenin kodu, logosu, görselleri veya metinleri telif nedeniyle kopyalanmamıştır; kendi marka öğelerinizi ve içeriğinizi kullanın.

## Birebir görünüm için tipografi

Orijinal sitenin başlık yazısı, ticari lisanslı çok ağır–dar bir grotesk ailedir (Druk tarzı; ücretsiz dağıtılamaz). Şablon varsayılan olarak en yakın açık kaynak karşılıkları kullanır: başlıklar için **Anton**, gövde için **Public Sans** (Google Fonts). Fontu birebir eşlemek isterseniz:

1. Ticari fontun web lisansını satın alıp dosyalarını `templates/solidarity/fonts/` içine koyun.
2. `templates/solidarity/css/user.css` dosyasını oluşturun (şablon varsa otomatik yükler):

```css
@font-face {
    font-family: "MarkaDisplay";
    src: url("../fonts/marka-display.woff2") format("woff2");
    font-display: swap;
}

:root {
    --sol-font-display: "MarkaDisplay", "Anton", sans-serif;
    /* İsterseniz gövde fontunu da değiştirin: --sol-font-body: ... */
}
```

Tüm renkler de `:root` değişkenleriyle (`--sol-purple-950`, `--sol-orange` vb.) `user.css`'ten ince ayar yapılabilir.

## Önizleme

Joomla kurmadan görünümü incelemek için `preview/index.html` dosyasını tarayıcıda açın. Sayfadaki tüm içerik yer tutucudur ve şablonun gerçek CSS/JS dosyalarını kullanır.

## Kurulum

1. Şablon paketini üretin (aşağıdaki *Paketleme* bölümü) veya hazır `dist/tpl_solidarity_1.3.0.zip` dosyasını kullanın.
2. Joomla yönetici panelinde **System → Install → Extensions** üzerinden zip'i yükleyin.
3. **System → Site Template Styles** ekranında **solidarity**'yi varsayılan yapın.
4. Şablon stilini açıp **Marka** sekmesinden logo, site başlığı, slogan ve üstteki turuncu eylem düğmesinin (ör. bağış) adresini ayarlayın.

## Modül pozisyonları

| Pozisyon | Konum / amaç |
|---|---|
| `topbar` | En üstte koyu duyuru şeridi |
| `menu` | Ana menü (Menus modülünü buraya atayın, stil: *none*) |
| `search` | Menünün yanında arama kutusu |
| `hero` | Mor dev başlıklı giriş bandı (Özel HTML modülü önerilir) |
| `ticker` | Turuncu kayan slogan şeridi (Özel HTML; her slogan ayrı `<p>` olsun) |
| `breadcrumbs` | İçerik haritası |
| `top-a` | Krem bölüm — modüller otomatik **kart** olur, yan yana ızgara dizilir |
| `top-b` | Mor bant bölüm — ortalanmış başlık, istatistik/vurgu alanı |
| `sidebar` | İçerik yanı (yayınlanırsa düzen otomatik iki sütuna geçer) |
| `main-top` / `main-bottom` | Bileşen üstü/altı kart alanları |
| `bottom-a` / `bottom-b` | Alt krem / mor bölümler |
| `cta` | Turuncu büyük eylem çağrısı bandı |
| `signup` | Lavanta e-bülten bandı |
| `footer-a…d` | Koyu footer sütunları (*footer* chrome ile) |
| `social` | Footer'da sosyal bağlantılar |
| `copyright` | Yasal metin (boşsa şablon parametresindeki metin gösterilir) |
| `debug` | Hata ayıklama |

## Ana sayfa tarifi (birebir bölüm sırası)

Referans sitenin ana sayfa akışı sırasıyla şu pozisyonlarla kurulur (tamamı `preview/index.html`'de örneklenmiştir):

| # | Bölüm | Pozisyon | İçerik |
|---|---|---|---|
| 1 | Fotoğraflı hero | `hero` | Özel HTML: başa `<div class="sol-hero__media"><img …></div>` (mor duoton otomatik), ardından `sol-eyebrow` + `<h1>` + butonlar |
| 2 | Kayan şerit | `ticker` | Özel HTML: her slogan ayrı `<p>★ …</p>` |
| 3 | Misyon girişi | `top-a` | Özel HTML, modül sınıfı **sol-plain** → kartsız ortalanmış metin + buton |
| 4 | Gündem ızgarası | `top-b` | Özel HTML: `sol-grid-3` içinde `sol-issue` blokları (`images/icons/` piktogramlarıyla) |
| 5 | Aday kartları | `bottom-a` | Özel HTML: `sol-person` kartları (fotoğraf otomatik duoton, `sol-tag` şehir çipi) |
| 6 | Şehir/şube ızgarası | `bottom-b` | Özel HTML: `sol-grid-4` içinde `sol-chip` bağlantıları |
| 7 | Haberler | ana içerik | Menü öğesini com_content *Featured/Blog* düzenine bağlayın — kartlara otomatik dönüşür |
| 8 | Turuncu çağrı bandı | `cta` | Özel HTML: `<h2>` + `sol-btn sol-btn--dark` |
| 9 | Kayıt bandı | `signup` | Form modülü: e-posta + posta kodu (`sol-input--small`) + `sol-btn sol-btn--accent` |
| 10 | Footer | `footer-a…d`, `social`, `copyright` | Sütunlar, `sol-social-icon` ikon düğmeleri, `sol-disclaimer` yasal beyan kutusu |

## Renk paleti

Parti markalarında yaygın belgelenen resmî mor **#582C83** paletin ana rengidir; koyu/açık tonlar (`--sol-purple-950/900/700`), lavanta (`#C9B5EC`), krem (`#F7F1E6`), canlı turuncu (`#FF5C2B`) ve altın (`#FFC531`) ile tamamlanır. Tümü `:root` değişkeni olarak tanımlıdır ve `user.css`'ten ezilebilir.

## İkonlar

`images/icons/` altında krem renkli minimal SVG ikonlar bulunur: sosyal (instagram, x, youtube, facebook) ve gündem piktogramları (wage, health, housing, climate, justice, megaphone — `sol-issue` blokları için). Footer'daki `social` pozisyonuna Özel HTML modülüyle şöyle eklenir:

```html
<a class="sol-social-icon" href="https://instagram.com/hesabiniz">
    <img src="templates/solidarity/images/icons/instagram.svg" alt="">
    <span class="visually-hidden">Instagram</span>
</a>
```

## Yardımcı CSS sınıfları

`sol-btn`, `sol-btn--accent`, `sol-btn--ghost`, `sol-btn--light`, `sol-btn--dark`, `sol-eyebrow`, `sol-stat`, `sol-grid-2/3/4`, `sol-photo-duotone`, `sol-social-icon`, `sol-issue` (ikonlu gündem bloğu), `sol-person`/`sol-person__photo` (duoton aday kartı), `sol-tag` (altın çip), `sol-chip` (şehir bağlantısı), `sol-disclaimer` (yasal beyan kutusu), `sol-plain` (modül sınıfı — kart görünümünü kaldırır).

## Paketleme

```bash
./build.sh   # dist/tpl_solidarity_<sürüm>.zip üretir
```

## Gereksinimler

- Joomla 5.x (4.4 ile de uyumlu API kullanımı)
- Google Fonts erişimi (istenmezse şablon ayarından kapatılır; sistem yazı tiplerine düşer)
