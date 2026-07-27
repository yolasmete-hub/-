# Solidarity — Joomla 5 Şablonu

**Solidarity**, aktivist/hareket tarzı siteler için sıfırdan yazılmış özgün bir Joomla 5 site şablonudur. Tasarım dili: krem zemin üzerinde derin mor + canlı turuncu palet, kalın–dar büyük harfli başlık tipografisi (Anton), bayrak motifi, dev başlıklı hero bandı, kayan şerit (ticker), sert gölgeli kartlar, lavanta e-bülten bandı ve koyu mor footer.

> Not: Bu şablon workingfamilies.org sitesinin tasarım dilinden **esinlenerek** tamamen özgün olarak yazılmıştır. Sitenin kodu, logosu, görselleri veya metinleri kopyalanmamıştır; kendi marka öğelerinizi ve içeriğinizi kullanın.

## Önizleme

Joomla kurmadan görünümü incelemek için `preview/index.html` dosyasını tarayıcıda açın. Sayfadaki tüm içerik yer tutucudur ve şablonun gerçek CSS/JS dosyalarını kullanır.

## Kurulum

1. Şablon paketini üretin (aşağıdaki *Paketleme* bölümü) veya hazır `dist/tpl_solidarity_1.0.0.zip` dosyasını kullanın.
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
