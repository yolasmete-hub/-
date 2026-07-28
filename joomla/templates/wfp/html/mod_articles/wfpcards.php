<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 *
 * mod_articles için "wfpcards" yerleşimi: beyaz haber kartları —
 * kondanse turuncu tarih, altı çizili başlık, giriş metni, Read more butonu.
 * Modül ayarlarından Layout olarak "wfpcards" seçilir.
 */

defined('_JEXEC') or die;

$rows = $grouped ? array_merge(...array_values((array) $list)) : (array) $list;

if (empty($rows)) {
    return;
}
?>
<div class="blog-items wfp-latest-cards">
	<?php foreach ($rows as $item) : ?>
		<div class="blog-item">
			<div class="item-content">
				<?php if ($item->displayDate) : ?>
					<div class="article-info"><?php echo htmlspecialchars($item->displayDate, ENT_QUOTES, 'UTF-8'); ?></div>
				<?php endif; ?>
				<h2><a href="<?php echo htmlspecialchars($item->link, ENT_COMPAT, 'UTF-8', false); ?>"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></a></h2>
				<?php if ($params->get('show_introtext', 1)) : ?>
					<?php echo $item->displayIntrotext; ?>
				<?php endif; ?>
				<?php if ($params->get('show_readmore', 1)) : ?>
					<p class="readmore">
						<a class="btn" href="<?php echo htmlspecialchars($item->link, ENT_COMPAT, 'UTF-8', false); ?>">Read more<span class="visually-hidden">: <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></span></a>
					</p>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
