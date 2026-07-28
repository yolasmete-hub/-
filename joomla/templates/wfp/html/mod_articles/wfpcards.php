<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 *
 * mod_articles için "wfpcards" yerleşimi: kayan renkli kare kartlar
 * (sarı/siyah/turuncu dönüşümlü, dairesel rozet + altı çizili başlık).
 * Modül ayarlarından Layout olarak "wfpcards" seçilir.
 */

defined('_JEXEC') or die;

$rows = $grouped ? array_merge(...array_values((array) $list)) : (array) $list;

if (empty($rows)) {
    return;
}

$badge = '<svg viewBox="0 0 200 200" aria-hidden="true"><defs><path id="wfp-badge-circle-' . $module->id . '" d="M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0"/></defs>'
    . '<circle cx="100" cy="100" r="34" fill="none" stroke="currentColor" stroke-width="6"/>'
    . '<text fill="currentColor" font-family="Barlow Condensed, sans-serif" font-weight="700" font-size="30" letter-spacing="6">'
    . '<textPath href="#wfp-badge-circle-' . $module->id . '">WORKING &#9733; FAMILIES &#9733; PARTY</textPath></text></svg>';
?>
<div class="wfp-slides">
	<?php foreach ($rows as $item) : ?>
		<article class="wfp-slide">
			<a href="<?php echo htmlspecialchars($item->link, ENT_COMPAT, 'UTF-8', false); ?>">
				<span class="wfp-slide-logo" aria-hidden="true"><?php echo $badge; ?></span>
				<h2 class="wfp-slide-hed"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</a>
		</article>
	<?php endforeach; ?>
</div>
