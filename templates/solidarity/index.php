<?php

/**
 * @package     Solidarity
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

if ((int) $this->params->get('googleFonts', 1) === 1) {
    $wa->registerAndUseStyle(
        'template.solidarity.fonts',
        'https://fonts.googleapis.com/css2?family=Anton&family=Public+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400&display=swap'
    );
}

$wa->registerAndUseStyle('template.solidarity.main', 'templates/' . $this->template . '/css/template.css', ['version' => '1.0.0']);
$wa->registerAndUseScript('template.solidarity.main', 'templates/' . $this->template . '/js/template.js', ['version' => '1.0.0'], ['defer' => true]);

$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

$menu      = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Logo: configured media file or the bundled placeholder
$logoFile = (string) $this->params->get('logoFile', '');

if ($logoFile !== '') {
    // Strip the #joomlaImage media adapter fragment
    $logoFile = preg_replace('/#.*$/', '', $logoFile);
    $logo     = Uri::root(true) . '/' . ltrim($logoFile, '/');
} else {
    $logo = $this->baseurl . '/templates/' . $this->template . '/images/logo.svg';
}

$siteTitle   = htmlspecialchars((string) $this->params->get('siteTitle', ''), ENT_QUOTES, 'UTF-8');
$siteTagline = htmlspecialchars((string) $this->params->get('siteTagline', ''), ENT_QUOTES, 'UTF-8');
$donateUrl   = (string) $this->params->get('donateUrl', '');
$donateLabel = (string) $this->params->get('donateLabel', '');
$donateLabel = $donateLabel !== '' ? htmlspecialchars(Text::_($donateLabel), ENT_QUOTES, 'UTF-8') : Text::_('TPL_SOLIDARITY_DONATE_LABEL_DEFAULT');

$stickyHeader = (int) $this->params->get('stickyHeader', 1) === 1;
$backToTop    = (int) $this->params->get('backToTop', 1) === 1;
$footerText   = (string) $this->params->get('footerText', '');

$hasSidebar = $this->countModules('sidebar', true);

$bodyClasses   = ['site'];
$bodyClasses[] = $option ? htmlspecialchars(str_replace('_', '-', $option), ENT_QUOTES, 'UTF-8') : 'no-option';
$bodyClasses[] = $view ? 'view-' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8') : 'no-view';

if ($layout) {
    $bodyClasses[] = 'layout-' . htmlspecialchars($layout, ENT_QUOTES, 'UTF-8');
}

if ($task) {
    $bodyClasses[] = 'task-' . htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
}

if ($itemid) {
    $bodyClasses[] = 'itemid-' . (int) $itemid;
}

if ($pageclass) {
    $bodyClasses[] = htmlspecialchars($pageclass, ENT_QUOTES, 'UTF-8');
}

if ($menu !== null && $menu->home) {
    $bodyClasses[] = 'is-home';
}

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo implode(' ', $bodyClasses); ?>">

    <a class="sol-skip-link" href="#sol-main"><?php echo Text::_('TPL_SOLIDARITY_SKIP_TO_CONTENT'); ?></a>

    <?php if ($this->countModules('topbar', true)) : ?>
        <div class="sol-topbar">
            <div class="sol-container">
                <jdoc:include type="modules" name="topbar" style="none" />
            </div>
        </div>
    <?php endif; ?>

    <header class="sol-header<?php echo $stickyHeader ? ' sol-header--sticky' : ''; ?>" id="sol-header">
        <div class="sol-container sol-header__inner">
            <a class="sol-brand" href="<?php echo $this->baseurl; ?>/">
                <img class="sol-brand__logo" src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $siteTitle !== '' ? $siteTitle : $sitename; ?>">
                <?php if ($siteTitle !== '') : ?>
                    <span class="sol-brand__text">
                        <span class="sol-brand__title"><?php echo $siteTitle; ?></span>
                        <?php if ($siteTagline !== '') : ?>
                            <span class="sol-brand__tagline"><?php echo $siteTagline; ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            </a>

            <nav class="sol-nav" id="sol-nav" aria-label="<?php echo Text::_('TPL_SOLIDARITY_MAIN_NAV'); ?>" data-submenu-label="<?php echo Text::_('TPL_SOLIDARITY_TOGGLE_SUBMENU'); ?>">
                <jdoc:include type="modules" name="menu" style="none" />
                <?php if ($this->countModules('search', true)) : ?>
                    <div class="sol-nav__search">
                        <jdoc:include type="modules" name="search" style="none" />
                    </div>
                <?php endif; ?>
            </nav>

            <div class="sol-header__actions">
                <?php if ($donateUrl !== '') : ?>
                    <a class="sol-btn sol-btn--accent sol-header__cta" href="<?php echo htmlspecialchars($donateUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $donateLabel; ?></a>
                <?php endif; ?>
                <button class="sol-nav-toggle" type="button" aria-expanded="false" aria-controls="sol-nav">
                    <span class="sol-nav-toggle__bar"></span>
                    <span class="sol-nav-toggle__bar"></span>
                    <span class="sol-nav-toggle__bar"></span>
                    <span class="visually-hidden"><?php echo Text::_('TPL_SOLIDARITY_TOGGLE_NAV'); ?></span>
                </button>
            </div>
        </div>
    </header>

    <?php if ($this->countModules('hero', true)) : ?>
        <div class="sol-hero">
            <div class="sol-container">
                <jdoc:include type="modules" name="hero" style="none" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('ticker', true)) : ?>
        <div class="sol-ticker" data-sol-ticker>
            <div class="sol-ticker__track">
                <jdoc:include type="modules" name="ticker" style="none" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('breadcrumbs', true)) : ?>
        <div class="sol-breadcrumbs">
            <div class="sol-container">
                <jdoc:include type="modules" name="breadcrumbs" style="none" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('top-a', true)) : ?>
        <section class="sol-section sol-section--cream">
            <div class="sol-container">
                <jdoc:include type="modules" name="top-a" style="card" />
            </div>
        </section>
    <?php endif; ?>

    <?php if ($this->countModules('top-b', true)) : ?>
        <section class="sol-section sol-section--purple">
            <div class="sol-container">
                <jdoc:include type="modules" name="top-b" style="band" />
            </div>
        </section>
    <?php endif; ?>

    <main class="sol-main" id="sol-main">
        <div class="sol-container">
            <?php if ($this->countModules('main-top', true)) : ?>
                <div class="sol-main__top">
                    <jdoc:include type="modules" name="main-top" style="card" />
                </div>
            <?php endif; ?>

            <div class="sol-layout<?php echo $hasSidebar ? ' sol-layout--sidebar' : ''; ?>">
                <div class="sol-layout__content">
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </div>
                <?php if ($hasSidebar) : ?>
                    <aside class="sol-layout__sidebar">
                        <jdoc:include type="modules" name="sidebar" style="card" />
                    </aside>
                <?php endif; ?>
            </div>

            <?php if ($this->countModules('main-bottom', true)) : ?>
                <div class="sol-main__bottom">
                    <jdoc:include type="modules" name="main-bottom" style="card" />
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($this->countModules('bottom-a', true)) : ?>
        <section class="sol-section sol-section--cream">
            <div class="sol-container">
                <jdoc:include type="modules" name="bottom-a" style="card" />
            </div>
        </section>
    <?php endif; ?>

    <?php if ($this->countModules('bottom-b', true)) : ?>
        <section class="sol-section sol-section--purple">
            <div class="sol-container">
                <jdoc:include type="modules" name="bottom-b" style="band" />
            </div>
        </section>
    <?php endif; ?>

    <?php if ($this->countModules('signup', true)) : ?>
        <section class="sol-signup">
            <div class="sol-container">
                <jdoc:include type="modules" name="signup" style="none" />
            </div>
        </section>
    <?php endif; ?>

    <footer class="sol-footer">
        <div class="sol-container">
            <?php if ($this->countModules('footer-a', true) || $this->countModules('footer-b', true) || $this->countModules('footer-c', true) || $this->countModules('footer-d', true)) : ?>
                <div class="sol-footer__grid">
                    <?php foreach (['footer-a', 'footer-b', 'footer-c', 'footer-d'] as $footerPosition) : ?>
                        <?php if ($this->countModules($footerPosition, true)) : ?>
                            <div class="sol-footer__col">
                                <jdoc:include type="modules" name="<?php echo $footerPosition; ?>" style="footer" />
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($this->countModules('social', true)) : ?>
                <div class="sol-footer__social">
                    <jdoc:include type="modules" name="social" style="none" />
                </div>
            <?php endif; ?>

            <div class="sol-footer__legal">
                <?php if ($this->countModules('copyright', true)) : ?>
                    <jdoc:include type="modules" name="copyright" style="none" />
                <?php elseif ($footerText !== '') : ?>
                    <?php echo $footerText; ?>
                <?php else : ?>
                    <p>&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <?php if ($backToTop) : ?>
        <button class="sol-back-to-top" type="button" data-sol-back-to-top hidden>
            <span aria-hidden="true">&uarr;</span>
            <span class="visually-hidden"><?php echo Text::_('TPL_SOLIDARITY_BACK_TO_TOP'); ?></span>
        </button>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
