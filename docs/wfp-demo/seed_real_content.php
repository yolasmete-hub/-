<?php
// wfp teması — gerçek workingfamilies.org ana sayfa içeriğine hizalama.
// Joomla kökünden çalıştırılır: php seed_real_content.php
// Yaptıkları:
//   - Ana menü gerçek yapıya çevrilir (Our 2026 Candidates, Latest News, Events,
//     Working Families Guarantee); eski sayfalar "Hidden Pages" menüsüne taşınır
//   - Get Active menüsü gerçek 5 öğeyle yenilenir
//   - "Tertiary Nav" menüsü kurulur (üst siyah şerit + footer'da kullanılır)
//   - Footer Menu ve Footer Legal gerçek öğelerle yenilenir
//   - Manşet, Feature, Contribute, Latest Extras, Footer Contact/Disclaimer
//     modül içerikleri gerçek metinlerle güncellenir
define('_JEXEC', 1);
define('JPATH_BASE', getcwd());
require JPATH_BASE . '/includes/defines.php';
require JPATH_BASE . '/includes/framework.php';

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Content;
use Joomla\CMS\Table\Menu;

$container = Factory::getContainer();
$container->alias('session', 'session.cli')
    ->alias('JSession', 'session.cli')
    ->alias(\Joomla\CMS\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\SessionInterface::class, 'session.cli');
Factory::$application = $container->get(\Joomla\Console\Application::class);
$db = Factory::getDbo();

$adminId = 272;
$now = '2026-07-28 12:00:00';
$latestCatId = (int) $db->setQuery("SELECT id FROM #__categories WHERE alias='latest' AND extension='com_content'")->loadResult();
$componentId = (int) $db->setQuery("SELECT extension_id FROM #__extensions WHERE element='com_content' AND type='component'")->loadResult();

// ---------- 1) Yeni makaleler ----------
$newArticles = [
    'Our 2026 Candidates' => '<p>Meet the nurses, teachers, drivers and organizers running on our line this year — every one of them pledged to answer to working people, not corporate donors.</p>',
    'Working Families Guarantee' => '<p>Our promise to every family: a good job, a safe home, quality healthcare and schools that work — guaranteed.</p>',
    'Become a WFP Member' => '<p>Membership is the backbone of our movement. Join thousands of working people building power in their communities.</p>',
    'Make Your Plan to Vote' => '<p>Check your registration, find your polling place, and make a plan to vote in the next election.</p>',
];

$ids = [];
foreach ($newArticles as $title => $intro) {
    $t = new Content($db);
    $data = [
        'title' => $title, 'alias' => '', 'introtext' => $intro, 'fulltext' => '',
        'state' => 1, 'catid' => 2, 'created' => $now, 'created_by' => $adminId,
        'publish_up' => $now, 'attribs' => '{}', 'metadata' => '{"robots":"","author":"","rights":""}',
        'images' => '{}', 'urls' => '{}', 'language' => '*', 'access' => 1,
        'featured' => 0, 'metadesc' => '', 'metakey' => '',
    ];
    if (!$t->bind($data) || !$t->check() || !$t->store()) {
        echo "Makale hatası ({$title}): " . $t->getError() . "\n";
        continue;
    }
    $ids[$title] = (int) $t->id;
    echo "Makale #{$t->id}: {$title}\n";
}

// ---------- 2) Menü türleri ----------
foreach (['hidden' => 'Hidden Pages', 'tertiarynav' => 'Tertiary Nav'] as $mt => $mtTitle) {
    $db->setQuery("INSERT INTO #__menu_types (menutype, title, description, client_id) VALUES (" .
        $db->quote($mt) . ", " . $db->quote($mtTitle) . ", '', 0)")->execute();
    echo "Menü türü: {$mtTitle}\n";
}

// Eski ana menü sayfalarını Hidden Pages'e taşı (linkler kırılmasın diye silinmiyor)
$db->setQuery("UPDATE #__menu SET menutype='hidden' WHERE id IN (102,103,105)")->execute();
$db->setQuery("UPDATE #__menu SET title='Latest News' WHERE id = 104")->execute();
echo "About/Our Fights/Take Action -> Hidden Pages; News -> Latest News\n";

// ---------- 3) Eski Get Active / Footer öğelerini sil ----------
foreach ([106, 107, 109, 116, 110, 112, 117, 118, 114, 115] as $oldId) {
    $t = new Menu($db);
    if ($t->load($oldId)) {
        $t->delete($oldId);
    }
}
echo "Eski Get Active / Footer menü öğeleri silindi\n";

// ---------- 4) Yeni menü öğeleri ----------
function wfp_menu_item($db, $menutype, $title, $type, $link, $componentId, $params = [], $alias = '')
{
    $t = new Menu($db);
    $data = [
        'menutype' => $menutype, 'title' => $title, 'alias' => $alias, 'path' => $alias,
        'link' => $link, 'type' => $type, 'published' => 1,
        'component_id' => $type === 'component' ? $componentId : 0,
        'access' => 1, 'language' => '*', 'client_id' => 0,
        'params' => json_encode($params), 'browserNav' => 0, 'template_style_id' => 0, 'img' => '',
    ];
    $t->setLocation(1, 'last-child');
    if (!$t->bind($data) || !$t->check() || !$t->store()) {
        echo "Menü öğesi hatası ({$title}): " . $t->getError() . "\n";
        return 0;
    }
    echo "Menü öğesi #{$t->id}: {$menutype} / {$title}\n";
    return (int) $t->id;
}

$art = fn ($id) => 'index.php?option=com_content&view=article&id=' . $id;
$blogParams = ['layout_type' => 'blog', 'num_leading_articles' => 0, 'num_intro_articles' => 9, 'num_columns' => 3, 'show_page_heading' => 1];

// Ana menü (gerçek birincil nav)
$candId = wfp_menu_item($db, 'mainmenu', 'Our 2026 Candidates', 'component', $art($ids['Our 2026 Candidates']), $componentId, ['show_title' => 1]);
wfp_menu_item($db, 'mainmenu', 'Events', 'url', '#', 0);
wfp_menu_item($db, 'mainmenu', 'Working Families Guarantee', 'component', $art($ids['Working Families Guarantee']), $componentId, ['show_title' => 1]);

// Get Active (gerçek 5 öğe)
wfp_menu_item($db, 'getactive', 'Become a WFP Member', 'component', $art($ids['Become a WFP Member']), $componentId, ['show_title' => 1]);
wfp_menu_item($db, 'getactive', 'Make your plan to vote', 'component', $art($ids['Make Your Plan to Vote']), $componentId, ['show_title' => 1]);
wfp_menu_item($db, 'getactive', 'Join a Welcome Gathering', 'url', '#', 0);
wfp_menu_item($db, 'getactive', 'Volunteer with WFP', 'url', '#', 0);
wfp_menu_item($db, 'getactive', 'Apply for our endorsement', 'url', '#', 0);

// Footer Menu (gerçek büyük nav; Sign Up ve Donate sarı alt çizgili)
wfp_menu_item($db, 'footermenu', 'Our 2026 Candidates', 'component', $art($ids['Our 2026 Candidates']), $componentId, ['show_title' => 1], 'candidates-ftr');
wfp_menu_item($db, 'footermenu', 'Latest News', 'component', 'index.php?option=com_content&view=category&layout=blog&id=' . $latestCatId, $componentId, $blogParams, 'latest-news-ftr');
wfp_menu_item($db, 'footermenu', 'Events', 'url', '#', 0, [], 'events-ftr');
wfp_menu_item($db, 'footermenu', 'Sign Up', 'url', '#', 0, ['menu-anchor_css' => 'wfp-underline'], 'sign-up-ftr');
wfp_menu_item($db, 'footermenu', 'Donate', 'url', '#donate', 0, ['menu-anchor_css' => 'wfp-underline'], 'donate-ftr');

// Tertiary Nav (üst şerit + footer ikinci satır)
wfp_menu_item($db, 'tertiarynav', 'About', 'component', $art(4), $componentId, ['show_title' => 1], 'about-tert');
wfp_menu_item($db, 'tertiarynav', 'Get Active', 'component', $art(5), $componentId, ['show_title' => 1], 'get-active-tert');
wfp_menu_item($db, 'tertiarynav', 'Store', 'url', '#', 0);
wfp_menu_item($db, 'tertiarynav', 'Sign Up', 'url', '#', 0, ['menu-anchor_css' => 'wfp-underline'], 'sign-up-tert');

// Footer Legal (gerçek alt küçük menü)
wfp_menu_item($db, 'footerlegal', 'Jobs', 'url', '#', 0);
wfp_menu_item($db, 'footerlegal', 'Media Center', 'url', '#', 0);
wfp_menu_item($db, 'footerlegal', 'Public Filings', 'url', '#', 0);
wfp_menu_item($db, 'footerlegal', 'Media Inquiries', 'url', '#', 0);
wfp_menu_item($db, 'footerlegal', 'Contact', 'url', 'mailto:hello@example.org', 0, [], 'contact-ftr');
wfp_menu_item($db, 'footerlegal', 'Privacy Policy', 'component', $art(8), $componentId, ['show_title' => 1], 'privacy-ftr');

(new Menu($db))->rebuild(1);

// Ana menü sırası: Home, Our 2026 Candidates, Latest News, Events, Guarantee
// (Candidates yeni eklendiği için Latest News'ün arkasında; yer değiştir)
if ($candId) {
    $db->setQuery("UPDATE #__menu a JOIN #__menu b ON a.id = 104 AND b.id = {$candId}
        SET a.lft=b.lft, a.rgt=b.rgt, b.lft=a.lft, b.rgt=a.rgt")->execute();
}

// ---------- 5) Modül içerikleri ----------
$hero = <<<HTML
<h1>Making our nation work <strong>for the many, not the few.</strong></h1>
HTML;
$db->setQuery("UPDATE #__modules SET content=" . $db->quote($hero) . " WHERE id=110")->execute();

$feature = <<<HTML
<span class="wfp-feature-sub">Working Families Party</span>
<h2>We're building a multiracial party of working people to transform our country.</h2>
<p>State by state and community by community, WFP is building a political home for all of us who see bigotry, bailouts, and business as usual in our political system and ask, "Is that the best we can do?"</p>
<div class="wfp-feature-links">
	<a class="wfp-under-link" href="index.php?option=com_content&amp;view=article&amp;id=4&amp;Itemid=102">Who We Are</a>
	<a class="wfp-under-link" href="index.php?option=com_content&amp;view=article&amp;id={$ids['Become a WFP Member']}">Membership</a>
	<a class="wfp-under-link" href="index.php?option=com_content&amp;view=article&amp;id={$ids['Our 2026 Candidates']}">Our Candidates</a>
</div>
HTML;
$db->setQuery("UPDATE #__modules SET content=" . $db->quote($feature) . " WHERE id=118")->execute();

$contribute = <<<HTML
<div class="wfp-cta-inner">
	<div>
		<h2>Contribute</h2>
		<p>Help us fight back and build real, lasting power for working people across the country with a contribution today.</p>
		<div class="wfp-donate-nav">
			<a class="wfp-btn" href="#">$10</a>
			<a class="wfp-btn" href="#">$27</a>
			<a class="wfp-btn" href="#">$100</a>
			<a class="wfp-btn" href="#">$250</a>
			<a class="wfp-btn wfp-btn-other" href="#">Other Amount</a>
		</div>
	</div>
</div>
HTML;
$db->setQuery("UPDATE #__modules SET content=" . $db->quote($contribute) . " WHERE id=112")->execute();

$db->setQuery("UPDATE #__modules SET title='Latest' WHERE id=117")->execute();

// Latest Extras: View All News + SMS CTA (Latest kartlarının altında)
$latestExtras = <<<HTML
<div class="wfp-latest-extras">
	<p class="wfp-view-all"><a class="wfp-under-link" href="index.php?option=com_content&amp;view=category&amp;layout=blog&amp;id={$latestCatId}&amp;Itemid=104">View All News</a></p>
	<aside class="wfp-tiny-cta">
		<p class="wfp-tiny-cta-sub">Stay Up to Date:</p>
		<p class="wfp-tiny-cta-hed">Text <b>'WFP'</b> to <b>30403</b></p>
	</aside>
</div>
HTML;

$contact = <<<HTML
<h2>Contact By Mail</h2>
<p>Working Families Party<br>77 Sands St. #6<br>Brooklyn, NY 11201</p>
<p class="wfp-made">Made with <a href="#" rel="noopener">Middle Seat</a></p>
HTML;
$db->setQuery("UPDATE #__modules SET content=" . $db->quote($contact) . " WHERE id=121")->execute();

$disclaimer = <<<HTML
<p>By taking action on this page, I affirm my intent to join or continue as a member of the Working Families Party, and to receive periodic updates from WFP.</p>
<div class="wfp-paid-for">Paid For By Working Families Party</div>
HTML;
$db->setQuery("UPDATE #__modules SET content=" . $db->quote($disclaimer) . " WHERE id=122")->execute();

// footer-b modülü artık Tertiary Nav gösterir
$db->setQuery("UPDATE #__modules SET title='Footer Tertiary', params=REPLACE(params, '\"menutype\":\"footerlegal\"', '\"menutype\":\"tertiarynav\"') WHERE id=120")->execute();

// ---------- 6) Yeni modüller ----------
function wfp_add_module($db, $title, $position, $module, $content, $params, $showtitle, $menuid, $ordering = 1)
{
    $query = $db->getQuery(true)
        ->insert('#__modules')
        ->columns($db->quoteName(['title', 'note', 'content', 'ordering', 'position', 'checked_out_time', 'publish_up', 'publish_down', 'published', 'module', 'access', 'showtitle', 'params', 'client_id', 'language']))
        ->values(implode(',', [
            $db->quote($title), $db->quote(''), $db->quote($content), (int) $ordering,
            $db->quote($position), 'NULL', 'NULL', 'NULL', 1, $db->quote($module),
            1, (int) $showtitle, $db->quote(json_encode($params)), 0, $db->quote('*'),
        ]));
    $db->setQuery($query)->execute();
    $id = (int) $db->insertid();
    $db->setQuery("INSERT INTO #__modules_menu (moduleid, menuid) VALUES ({$id}, " . (int) $menuid . ")")->execute();
    echo "Modül #{$id}: {$title} ({$position})\n";
    return $id;
}

$menuParams = fn ($mt) => [
    'menutype' => $mt, 'base' => '', 'startLevel' => 1, 'endLevel' => 0,
    'showAllChildren' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900,
];

wfp_add_module($db, 'Header Tertiary', 'topbar', 'mod_menu', '', $menuParams('tertiarynav'), 0, 0);
wfp_add_module($db, 'Footer Small Menu', 'footer-d', 'mod_menu', '', $menuParams('footerlegal'), 0, 0);
wfp_add_module($db, 'Latest Extras', 'main-top', 'mod_custom', $latestExtras, [
    'prepare_content' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900, 'style' => '0',
], 0, 101, 2);

// ---------- 7) Tema stil parametreleri: buton etiketleri ----------
$db->setQuery("UPDATE #__template_styles SET params = REPLACE(REPLACE(params,
    '\"donateLabel\":\"Donate\"', '\"donateLabel\":\"Contribute\"'),
    '\"joinLabel\":\"Join us\"', '\"joinLabel\":\"Sign Up\"')
    WHERE template='wfp' AND client_id=0")->execute();

echo "Bitti.\n";
