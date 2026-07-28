<?php
// Custom HTML module seeder for the wfp template demo — run from the joomla root.
define('_JEXEC', 1);
define('JPATH_BASE', getcwd());
require JPATH_BASE . '/includes/defines.php';
require JPATH_BASE . '/includes/framework.php';

use Joomla\CMS\Factory;

$db = Factory::getDbo();

$heroHtml = <<<HTML
<span class="wfp-kicker">People power since 2026</span>
<h1>A nation that works for the <em>many</em>, not the few</h1>
<p>We are working people building a movement — electing champions from our own communities and holding them accountable to us, not to corporate donors.</p>
<div class="wfp-hero-actions">
	<a class="wfp-btn wfp-btn-accent" href="index.php?option=com_content&amp;view=article&amp;id=5&amp;Itemid=105">Take action</a>
	<a class="wfp-btn wfp-btn-ghost" href="index.php?option=com_content&amp;view=article&amp;id=4&amp;Itemid=102">Who we are</a>
</div>
HTML;

$issuesHtml = <<<HTML
<ul class="wfp-issues">
	<li class="wfp-issue"><div class="wfp-issue-icon">🏠</div><h3><a href="#">Housing for all</a></h3><p>Rent stabilization, social housing and an end to speculative evictions.</p></li>
	<li class="wfp-issue"><div class="wfp-issue-icon">💼</div><h3><a href="#">Wages &amp; workers' rights</a></h3><p>A living wage, paid leave and the unfettered right to organize a union.</p></li>
	<li class="wfp-issue"><div class="wfp-issue-icon">🩺</div><h3><a href="#">Healthcare</a></h3><p>Quality, affordable care for every family — no one left behind.</p></li>
	<li class="wfp-issue"><div class="wfp-issue-icon">🌍</div><h3><a href="#">Climate justice</a></h3><p>Green jobs and clean air for the communities hit first and worst.</p></li>
	<li class="wfp-issue"><div class="wfp-issue-icon">📚</div><h3><a href="#">Public education</a></h3><p>Fully funded neighborhood schools and debt-free college.</p></li>
	<li class="wfp-issue"><div class="wfp-issue-icon">🗳️</div><h3><a href="#">Democracy</a></h3><p>Getting big money out of politics and making every vote count.</p></li>
</ul>
HTML;

$ctaHtml = <<<HTML
<div class="wfp-cta-inner">
	<div>
		<h2>Join the fight</h2>
		<p>Get campaign updates, actions near you, and news from the movement — straight to your inbox.</p>
	</div>
	<form action="#" method="post">
		<label class="skip-link" for="wfp-newsletter-email">Email</label>
		<input type="email" id="wfp-newsletter-email" name="email" placeholder="you@example.org" required>
		<button type="submit" class="wfp-btn wfp-btn-purple">Sign up</button>
	</form>
</div>
HTML;

$footerAHtml = <<<HTML
<ul>
	<li><a href="index.php?option=com_content&amp;view=article&amp;id=4&amp;Itemid=102">About us</a></li>
	<li><a href="index.php?option=com_content&amp;view=category&amp;layout=blog&amp;id=2&amp;Itemid=104">Latest news</a></li>
	<li><a href="index.php?option=com_content&amp;view=category&amp;layout=blog&amp;id=2&amp;Itemid=103">Our fights</a></li>
	<li><a href="index.php?option=com_content&amp;view=article&amp;id=5&amp;Itemid=105">Take action</a></li>
</ul>
HTML;

$footerBHtml = <<<HTML
<ul>
	<li><a href="#">Volunteer</a></li>
	<li><a href="#">Find your local chapter</a></li>
	<li><a href="#">Run for office</a></li>
	<li><a href="#donate">Donate</a></li>
</ul>
HTML;

$footerCHtml = <<<HTML
<ul>
	<li><a href="mailto:hello@example.org">hello@example.org</a></li>
	<li><a href="tel:+10000000000">+1 (000) 000-0000</a></li>
	<li>123 Movement St, Anytown</li>
</ul>
HTML;

$modules = [
    ['title' => 'Hero',         'position' => 'hero',     'content' => $heroHtml,    'showtitle' => 0],
    ['title' => 'Our fights',   'position' => 'top-a',    'content' => $issuesHtml,  'showtitle' => 1],
    ['title' => 'Join the fight', 'position' => 'cta',    'content' => $ctaHtml,     'showtitle' => 0],
    ['title' => 'Explore',      'position' => 'footer-a', 'content' => $footerAHtml, 'showtitle' => 1],
    ['title' => 'Get involved', 'position' => 'footer-b', 'content' => $footerBHtml, 'showtitle' => 1],
    ['title' => 'Contact',      'position' => 'footer-c', 'content' => $footerCHtml, 'showtitle' => 1],
];

$params = '{"prepare_content":0,"backgroundimage":"","layout":"_:default","moduleclass_sfx":"","cache":1,"cache_time":900,"cachemode":"static","style":"0"}';

foreach ($modules as $i => $m) {
    $query = $db->getQuery(true)
        ->insert('#__modules')
        ->columns($db->quoteName(['title', 'note', 'content', 'ordering', 'position', 'checked_out_time', 'publish_up', 'publish_down', 'published', 'module', 'access', 'showtitle', 'params', 'client_id', 'language']))
        ->values(implode(',', [
            $db->quote($m['title']), $db->quote(''), $db->quote($m['content']), $i + 1,
            $db->quote($m['position']), 'NULL', 'NULL', 'NULL', 1, $db->quote('mod_custom'),
            1, (int) $m['showtitle'], $db->quote($params), 0, $db->quote('*'),
        ]));
    $db->setQuery($query)->execute();
    $id = (int) $db->insertid();
    $db->setQuery("INSERT INTO #__modules_menu (moduleid, menuid) VALUES ({$id}, 0)")->execute();
    echo "Module #{$id}: {$m['title']} ({$m['position']})\n";
}

echo "Done.\n";
