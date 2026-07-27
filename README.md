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

1. Şablon paketini üretin (aşağıdaki *Paketleme* bölümü) veya hazır `dist/tpl_solidarity_1.1.0.zip` dosyasını kullanın.
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
| `signup` | Lavanta e-bülten bandı |
| `footer-a…d` | Koyu footer sütunları (*footer* chrome ile) |
| `social` | Footer'da sosyal bağlantılar |
| `copyright` | Yasal metin (boşsa şablon parametresindeki metin gösterilir) |
| `debug` | Hata ayıklama |

## Ana sayfa tarifi (workingfamilies.org düzenine eş yapı)

1. **hero** → Özel HTML: `<span class="sol-eyebrow">Üst başlık</span> <h1>Dev slogan <em>vurgulu söz</em></h1> <p>Alt metin</p> <a class="sol-btn sol-btn--accent">Katıl</a> <a class="sol-btn sol-btn--light">Bilgi</a>`
   - Orijinal sitedeki gibi **fotoğraflı hero** için modülün en başına ekleyin: `<div class="sol-hero__media"><img src="images/miting.jpg" alt=""></div>` — fotoğraf otomatik olarak mor duoton yıkamayla tam genişlik arka plana yerleşir.
2. **ticker** → Özel HTML: `<p>★ Slogan 1</p><p>★ Slogan 2</p>…`
3. **top-a** → 3 adet Özel HTML modülü (başlık açık) → otomatik kart ızgarası
4. **top-b** → Özel HTML: `sol-grid-3` + `sol-stat` sınıflarıyla istatistik bandı
5. **signup** → E-bülten form modülü
6. **footer-a…d** → menü/iletişim modülleri, **social** → sosyal bağlantılar

## Yardımcı CSS sınıfları

`sol-btn`, `sol-btn--accent`, `sol-btn--ghost`, `sol-btn--light`, `sol-eyebrow`, `sol-stat`, `sol-grid-2/3/4`, `sol-photo-duotone` (fotoğraflara mor-turuncu duoton efekti).

## Paketleme

```bash
./build.sh   # dist/tpl_solidarity_<sürüm>.zip üretir
```

## Gereksinimler

- Joomla 5.x (4.4 ile de uyumlu API kullanımı)
- Google Fonts erişimi (istenmezse şablon ayarından kapatılır; sistem yazı tiplerine düşer)
