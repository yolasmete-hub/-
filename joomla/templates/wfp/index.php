<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 *
 * Yönetilebilir bölgeler (Joomla yönetiminden düzenlenir):
 *  - Üst menü        : Menus » Main Menu  +  Site Modules » "Main Menu" (pozisyon: menu)
 *  - Manşet          : Site Modules » "Manşet (Hero)" (pozisyon: hero)
 *  - Get Active      : Menus » Get Active  +  Site Modules » "Get Active" (pozisyon: below-top)
 *  - Dava kartları   : Site Modules » "Our fights" (pozisyon: top-a)
 *  - Latest          : Site Modules » "Latest News" (pozisyon: main-top, kaynak: "Latest" kategorisi)
 *  - Siyah bant      : Site Modules » "Feature (Siyah Bant)" (pozisyon: main-bottom)
 *  - Contribute      : Site Modules » "Contribute" (pozisyon: cta)
 *  - Footer menü     : Menus » Footer Menu  +  Site Modules » "Footer Menu" (pozisyon: footer-a)
 *  - Footer küçük menü: Menus » Footer Legal  +  Site Modules » "Footer Legal" (pozisyon: footer-b)
 *  - Footer iletişim : Site Modules » "Footer Contact" (pozisyon: footer-c)
 *  - Disclaimer/PaidFor: Site Modules » "Footer Disclaimer" (pozisyon: copyright)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$wa    = $this->getWebAssetManager();
$menu  = $app->getMenu()->getActive();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$isHome   = $menu !== null && $menu->home;

$tagline     = htmlspecialchars((string) $this->params->get('siteTagline', ''), ENT_QUOTES, 'UTF-8');
$donateUrl   = htmlspecialchars((string) $this->params->get('donateUrl', '#'), ENT_QUOTES, 'UTF-8');
$donateLabel = htmlspecialchars((string) $this->params->get('donateLabel', 'Donate'), ENT_QUOTES, 'UTF-8');
$joinUrl     = htmlspecialchars((string) $this->params->get('joinUrl', '#'), ENT_QUOTES, 'UTF-8');
$joinLabel   = htmlspecialchars((string) $this->params->get('joinLabel', ''), ENT_QUOTES, 'UTF-8');

$socials = array_filter([
    'twitter'   => (string) $this->params->get('socialTwitter', ''),
    'facebook'  => (string) $this->params->get('socialFacebook', ''),
    'instagram' => (string) $this->params->get('socialInstagram', ''),
    'youtube'   => (string) $this->params->get('socialYoutube', ''),
]);

$socialIcons = [
    'twitter'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3l-4.9-6.4L6.4 22H3.3l7.3-8.3L1.6 2H8l4.4 5.9L18.9 2Zm-1.1 18h1.7L7.1 3.7H5.3L17.8 20Z"/></svg>',
    'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.7c0-.9.3-1.6 1.6-1.6h1.7V4.2c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.4H7.4V14h2.7v8h3.4Z"/></svg>',
    'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 4.3c2.5 0 2.8 0 3.8.1 2.5.1 3.7 1.3 3.8 3.8.1 1 .1 1.3.1 3.8s0 2.8-.1 3.8c-.1 2.5-1.3 3.7-3.8 3.8-1 .1-1.3.1-3.8.1s-2.8 0-3.8-.1c-2.5-.1-3.7-1.3-3.8-3.8-.1-1-.1-1.3-.1-3.8s0-2.8.1-3.8C4.5 5.7 5.7 4.5 8.2 4.4c1-.1 1.3-.1 3.8-.1ZM12 2C9.4 2 9.1 2 8.1 2.1 4.7 2.2 2.2 4.6 2.1 8.1 2 9.1 2 9.4 2 12s0 2.9.1 3.9c.1 3.4 2.6 5.9 6 6 1 .1 1.3.1 3.9.1s2.9 0 3.9-.1c3.4-.1 5.9-2.6 6-6 .1-1 .1-1.3.1-3.9s0-2.9-.1-3.9c-.1-3.4-2.6-5.9-6-6C14.9 2 14.6 2 12 2Zm0 4.9a5.1 5.1 0 1 0 0 10.2 5.1 5.1 0 0 0 0-10.2Zm0 8.4a3.3 3.3 0 1 1 0-6.6 3.3 3.3 0 0 1 0 6.6Zm5.3-9.8a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Z"/></svg>',
    'youtube'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M23 7.2s-.2-1.6-.9-2.2c-.8-.9-1.8-.9-2.2-.9C16.8 3.9 12 3.9 12 3.9s-4.8 0-7.9.2c-.4.1-1.4.1-2.2.9-.7.7-.9 2.2-.9 2.2S.8 9 .8 10.9v1.7c0 1.8.2 3.7.2 3.7s.2 1.6.9 2.2c.8.9 1.9.8 2.4.9 1.8.2 7.7.2 7.7.2s4.8 0 7.9-.2c.4-.1 1.4-.1 2.2-.9.7-.7.9-2.2.9-2.2s.2-1.8.2-3.7v-1.7C23.2 9 23 7.2 23 7.2ZM9.7 14.9V8.6l6.1 3.2-6.1 3.1Z"/></svg>',
];

// Template assets
$wa->registerAndUseStyle('template.wfp', 'templates/' . $this->template . '/css/template.css', ['version' => $this->version]);
$wa->registerAndUseScript('template.wfp', 'templates/' . $this->template . '/js/template.js', ['version' => $this->version], ['defer' => true]);

// Favicon
$this->addHeadLink(Uri::root(true) . '/templates/' . $this->template . '/images/favicon.svg', 'icon', 'rel', ['type' => 'image/svg+xml']);

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$hasSidebar   = $this->countModules('sidebar-right', true);
$hasGetActive = $this->countModules('below-top', true);
$pageClass    = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="site <?php echo $isHome ? 'view-home' : 'view-inner'; ?> <?php echo htmlspecialchars($pageClass, ENT_QUOTES, 'UTF-8'); ?>">
	<a class="skip-link" href="#wfp-main"><?php echo Text::_('TPL_WFP_SKIP_TO_CONTENT'); ?></a>

	<?php if ($this->countModules('topbar', true)) : ?>
		<div class="wfp-topbar">
			<div class="wfp-container">
				<jdoc:include type="modules" name="topbar" style="none" />
			</div>
		</div>
	<?php endif; ?>

	<!-- ÜST MENÜ — Menus » Main Menu / Site Modules » Main Menu (pozisyon: menu) -->
	<header class="wfp-header">
		<div class="wfp-container wfp-header-inner">
			<a class="wfp-brand" href="<?php echo Route::_('index.php'); ?>" aria-label="<?php echo $sitename; ?>">
				<span class="wfp-brand-mark" aria-hidden="true">
					<svg viewBox="0 0 44 44" role="img"><rect x="6" y="4" width="4" height="38" rx="1.4" fill="currentColor"/><path d="M12 6h26l-5.4 6.4L38 19H12V6Z" fill="var(--wfp-accent)"/></svg>
				</span>
				<span class="wfp-brand-text">
					<span class="wfp-brand-name"><?php echo $sitename; ?></span>
					<?php if ($tagline !== '') : ?>
						<span class="wfp-brand-tagline"><?php echo $tagline; ?></span>
					<?php endif; ?>
				</span>
			</a>

			<button class="wfp-nav-toggle" type="button" aria-expanded="false" aria-controls="wfp-nav" aria-label="<?php echo Text::_('TPL_WFP_MENU'); ?>">
				<span></span><span></span><span></span>
			</button>

			<nav id="wfp-nav" class="wfp-nav" aria-label="<?php echo Text::_('TPL_WFP_MAIN_NAV'); ?>">
				<jdoc:include type="modules" name="menu" style="none" />
				<div class="wfp-nav-actions">
					<?php if ($this->countModules('search', true)) : ?>
						<jdoc:include type="modules" name="search" style="none" />
					<?php endif; ?>
					<?php if ($socials) : ?>
						<ul class="wfp-social wfp-social-header">
							<?php foreach ($socials as $network => $url) : ?>
								<li>
									<a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo ucfirst($network); ?>" rel="noopener" target="_blank">
										<?php echo $socialIcons[$network]; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ($joinLabel !== '') : ?>
						<a class="wfp-btn wfp-btn-ghost" href="<?php echo $joinUrl; ?>"><?php echo $joinLabel; ?></a>
					<?php endif; ?>
					<a class="wfp-btn wfp-btn-accent" href="<?php echo $donateUrl; ?>"><span class="wfp-heart" aria-hidden="true"><svg viewBox="0 0 24 24" width="15" height="15" style="display:inline-block;vertical-align:-2px"><path fill="currentColor" d="M12 21s-7.5-4.9-10-9.2C.3 8.9 1.6 5 5.1 4.2 7.3 3.7 9.2 4.6 12 7c2.8-2.4 4.7-3.3 6.9-2.8 3.5.8 4.8 4.7 3.1 7.6C19.5 16.1 12 21 12 21Z"/></svg></span><?php echo $donateLabel; ?></a>
				</div>
			</nav>
		</div>
	</header>

	<!-- MANŞET + GET ACTIVE — Site Modules » "Manşet (Hero)" (hero) ve "Get Active" (below-top) -->
	<?php if ($this->countModules('hero', true) || $hasGetActive) : ?>
		<section class="wfp-hero">
			<div class="wfp-container wfp-hero-grid">
				<div class="wfp-hero-content">
					<jdoc:include type="modules" name="hero" style="none" />
				</div>
				<?php if ($hasGetActive) : ?>
					<aside class="wfp-getactive-slot">
						<jdoc:include type="modules" name="below-top" style="wfpgetactive" />
					</aside>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- SİYAH BANT — Site Modules » "Feature (Siyah Bant)" (main-bottom) -->
	<?php if ($this->countModules('main-bottom', true)) : ?>
		<section class="wfp-feature-band">
			<div class="wfp-container">
				<jdoc:include type="modules" name="main-bottom" style="none" />
			</div>
		</section>
	<?php endif; ?>

	<!-- DAVA KARTLARI — Site Modules » "Our fights" (top-a) -->
	<?php if ($this->countModules('top-a', true)) : ?>
		<section class="wfp-section wfp-section-topa">
			<div class="wfp-container">
				<jdoc:include type="modules" name="top-a" style="wfpsection" />
			</div>
		</section>
	<?php endif; ?>

	<?php if ($this->countModules('top-b', true)) : ?>
		<section class="wfp-section wfp-section-topb">
			<div class="wfp-container">
				<jdoc:include type="modules" name="top-b" style="wfpsection" />
			</div>
		</section>
	<?php endif; ?>

	<?php if ($this->countModules('breadcrumbs', true) && !$isHome) : ?>
		<div class="wfp-breadcrumbs">
			<div class="wfp-container">
				<jdoc:include type="modules" name="breadcrumbs" style="none" />
			</div>
		</div>
	<?php endif; ?>

	<!-- LATEST — Site Modules » "Latest News" (main-top, kaynak: Latest kategorisi) -->
	<main id="wfp-main" class="wfp-main">
		<div class="wfp-container">
			<jdoc:include type="modules" name="main-top" style="wfpsection" />
			<div class="wfp-content-wrap <?php echo $hasSidebar ? 'has-sidebar' : ''; ?>">
				<div class="wfp-content">
					<jdoc:include type="message" />
					<jdoc:include type="component" />
				</div>
				<?php if ($hasSidebar) : ?>
					<aside class="wfp-sidebar">
						<jdoc:include type="modules" name="sidebar-right" style="wfpcard" />
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</main>

	<!-- CONTRIBUTE — Site Modules » "Contribute" (cta) -->
	<?php if ($this->countModules('cta', true)) : ?>
		<section class="wfp-cta">
			<div class="wfp-container">
				<jdoc:include type="modules" name="cta" style="none" />
			</div>
		</section>
	<?php endif; ?>

	<!-- FOOTER — gerçek .ftr yapısı:
	     footer-a: büyük menü (Menus » Footer Menu)
	     footer-b: küçük büyük-harf menü (Menus » Tertiary Nav)
	     footer-d: alt küçük menü (Menus » Footer Legal)
	     footer-c: posta adresi + "Made with" (Site Modules » Footer Contact)
	     copyright: disclaimer + Paid For (Site Modules » Footer Disclaimer) -->
	<footer class="wfp-footer">
		<div class="wfp-container">
			<div class="wfp-ftr-main">
				<a class="wfp-brand wfp-brand-footer" href="<?php echo Route::_('index.php'); ?>" aria-label="<?php echo $sitename; ?>">
					<span class="wfp-brand-mark" aria-hidden="true">
						<svg viewBox="0 0 44 44" role="img"><rect x="6" y="4" width="4" height="38" rx="1.4" fill="currentColor"/><path d="M12 6h26l-5.4 6.4L38 19H12V6Z" fill="var(--wfp-accent)"/></svg>
					</span>
					<span class="wfp-brand-name"><?php echo $sitename; ?></span>
				</a>
				<?php if ($this->countModules('footer-a', true)) : ?>
					<nav class="wfp-ftr-nav" aria-label="Footer">
						<jdoc:include type="modules" name="footer-a" style="none" />
					</nav>
				<?php endif; ?>
			</div>

			<div class="wfp-ftr-secondary">
				<?php if ($this->countModules('footer-b', true)) : ?>
					<div class="wfp-ftr-tertnav">
						<jdoc:include type="modules" name="footer-b" style="none" />
					</div>
				<?php endif; ?>
				<?php if ($socials) : ?>
					<ul class="wfp-social">
						<?php foreach ($socials as $network => $url) : ?>
							<li>
								<a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo ucfirst($network); ?>" rel="noopener" target="_blank">
									<?php echo $socialIcons[$network]; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="wfp-ftr-low">
				<?php if ($this->countModules('footer-d', true)) : ?>
					<div class="wfp-ftr-smnav">
						<jdoc:include type="modules" name="footer-d" style="none" />
					</div>
				<?php endif; ?>
				<?php if ($this->countModules('footer-c', true)) : ?>
					<div class="wfp-ftr-contact">
						<jdoc:include type="modules" name="footer-c" style="none" />
					</div>
				<?php endif; ?>
			</div>

			<?php if ($this->countModules('copyright', true)) : ?>
				<div class="wfp-ftr-disclaimer">
					<jdoc:include type="modules" name="copyright" style="none" />
				</div>
			<?php endif; ?>

			<div class="wfp-footer-bottom">
				<p>&copy; <?php echo date('Y') . ' ' . $sitename; ?></p>
			</div>
		</div>
	</footer>

	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
