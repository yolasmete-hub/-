<?php
// wfp teması — yönetilebilir bölge yapısı kurulumu. Joomla kökünden çalıştırılır:
//   php seed_structure.php
// Kurduğu yapı:
//   - "Latest" kategorisi (haberler buradan beslenir) + mevcut 3 haber bu kategoriye taşınır
//   - Menüler: Get Active, Footer Menu, Footer Legal (+ öğeleri)
//   - Makaleler: Volunteer, Run for Office, Privacy Policy
//   - Modüller: Get Active (below-top), Latest News (main-top, mod_articles/wfpcards),
//     Feature (main-bottom), Footer Menu (footer-a), Footer Legal (footer-b),
//     Footer Contact (footer-c), Footer Disclaimer (copyright)
//   - Manşet ve Contribute modüllerinin adları netleştirilir
define('_JEXEC', 1);
define('JPATH_BASE', getcwd());
require JPATH_BASE . '/includes/defines.php';
require JPATH_BASE . '/includes/framework.php';

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Category;
use Joomla\CMS\Table\Content;
use Joomla\CMS\Table\Menu;

$container = Factory::getContainer();
$container->alias('session', 'session.cli')
    ->alias('JSession', 'session.cli')
    ->alias(\Joomla\CMS\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\SessionInterface::class, 'session.cli');
$app = $container->get(\Joomla\Console\Application::class);
Factory::$application = $app;
$db = Factory::getDbo();

$adminId = 272;
$now     = '2026-07-28 12:00:00';

// ---------- 1) "Latest" kategorisi ----------
$cat = new Category($db);
$cat->setLocation(1, 'last-child'); // 1 = kategori kök düğümü
$catData = [
    'extension'   => 'com_content',
    'title'       => 'Latest',
    'alias'       => 'latest',
    'description' => 'Ana sayfadaki Latest alanını besleyen haber kategorisi.',
    'published'   => 1,
    'access'      => 1,
    'params'      => '{"category_layout":"","image":"","thumbnail":""}',
    'metadata'    => '{"author":"","robots":""}',
    'metadesc'    => '',
    'metakey'     => '',
    'created_user_id' => $adminId,
    'language'    => '*',
];
if (!$cat->bind($catData) || !$cat->check() || !$cat->store()) {
    die('Kategori oluşturulamadı: ' . $cat->getError() . "\n");
}
$latestCatId = (int) $cat->id;
echo "Kategori 'Latest' #{$latestCatId}\n";

// Mevcut 3 haber makalesini Latest kategorisine taşı; öne çıkarmayı kaldır
$db->setQuery("UPDATE #__content SET catid = {$latestCatId} WHERE id IN (1,2,3)")->execute();
$db->setQuery("UPDATE #__content SET featured = 0")->execute();
$db->setQuery("DELETE FROM #__content_frontpage")->execute();
echo "Haberler Latest kategorisine taşındı, featured temizlendi\n";

// ---------- 2) Yeni makaleler ----------
$newArticles = [
    'Volunteer' => [
        'intro' => '<p>Knock doors, make calls, host a house meeting. Sign up and an organizer near you will get in touch.</p>',
        'full'  => '<p>No experience needed — we train every volunteer. Bring a friend.</p>',
    ],
    'Run for Office' => [
        'intro' => '<p>The best candidates are the people our communities already trust. If that is you, we want to talk.</p>',
        'full'  => '<p>Our candidate pipeline provides training, mentorship and a movement behind you.</p>',
    ],
    'Privacy Policy' => [
        'intro' => '<p>We respect your privacy. This page explains what data we collect and how we use it.</p>',
        'full'  => '<p>We never sell your data. You can unsubscribe from our communications at any time.</p>',
    ],
];

$newIds = [];
foreach ($newArticles as $title => $a) {
    $table = new Content($db);
    $data = [
        'title' => $title, 'alias' => '', 'introtext' => $a['intro'], 'fulltext' => $a['full'],
        'state' => 1, 'catid' => 2, 'created' => $now, 'created_by' => $adminId,
        'publish_up' => $now, 'attribs' => '{}', 'metadata' => '{"robots":"","author":"","rights":""}',
        'images' => '{}', 'urls' => '{}', 'language' => '*', 'access' => 1,
        'featured' => 0, 'metadesc' => '', 'metakey' => '',
    ];
    if (!$table->bind($data) || !$table->check() || !$table->store()) {
        echo "Makale hatası ({$title}): " . $table->getError() . "\n";
        continue;
    }
    $newIds[$title] = (int) $table->id;
    echo "Makale #{$table->id}: {$title}\n";
}

// ---------- 3) Menü türleri ----------
$menuTypes = [
    'getactive'   => 'Get Active',
    'footermenu'  => 'Footer Menu',
    'footerlegal' => 'Footer Legal',
];
foreach ($menuTypes as $mt => $mtTitle) {
    $db->setQuery("INSERT INTO #__menu_types (menutype, title, description, client_id) VALUES (" .
        $db->quote($mt) . ", " . $db->quote($mtTitle) . ", '', 0)")->execute();
    echo "Menü türü: {$mtTitle}\n";
}

// ---------- 4) Menü öğeleri ----------
$componentId = (int) $db->setQuery("SELECT extension_id FROM #__extensions WHERE element='com_content' AND type='component'")->loadResult();

$menuItems = [
    // Get Active
    ['menutype' => 'getactive', 'title' => 'Volunteer',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=' . $newIds['Volunteer'],
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    ['menutype' => 'getactive', 'title' => 'Run for office',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=' . $newIds['Run for Office'],
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    ['menutype' => 'getactive', 'title' => 'Take action',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=5',
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    ['menutype' => 'getactive', 'title' => 'Donate',
     'type' => 'url', 'link' => '#donate', 'component_id' => 0, 'params' => []],
    // Footer Menu
    ['menutype' => 'footermenu', 'title' => 'About us',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=4',
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    ['menutype' => 'footermenu', 'title' => 'Our fights',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=category&layout=blog&id=2',
     'component_id' => $componentId, 'params' => ['layout_type' => 'blog', 'show_page_heading' => 1]],
    ['menutype' => 'footermenu', 'title' => 'Latest news',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=category&layout=blog&id=' . $latestCatId,
     'component_id' => $componentId, 'params' => ['layout_type' => 'blog', 'num_leading_articles' => 0, 'num_intro_articles' => 9, 'num_columns' => 3, 'show_page_heading' => 1]],
    ['menutype' => 'footermenu', 'title' => 'Take action',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=5',
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    // Footer Legal
    ['menutype' => 'footerlegal', 'title' => 'Privacy policy',
     'type' => 'component', 'link' => 'index.php?option=com_content&view=article&id=' . $newIds['Privacy Policy'],
     'component_id' => $componentId, 'params' => ['show_title' => 1]],
    ['menutype' => 'footerlegal', 'title' => 'Contact',
     'type' => 'url', 'link' => 'mailto:hello@example.org', 'component_id' => 0, 'params' => []],
];

foreach ($menuItems as $m) {
    $table = new Menu($db);
    $data = [
        'menutype' => $m['menutype'], 'title' => $m['title'], 'alias' => '', 'path' => '',
        'link' => $m['link'], 'type' => $m['type'], 'published' => 1,
        'component_id' => $m['component_id'], 'access' => 1, 'language' => '*',
        'client_id' => 0, 'params' => json_encode($m['params']),
        'browserNav' => 0, 'template_style_id' => 0, 'img' => '',
    ];
    $table->setLocation(1, 'last-child');
    if (!$table->bind($data) || !$table->check() || !$table->store()) {
        echo "Menü öğesi hatası ({$m['title']}): " . $table->getError() . "\n";
        continue;
    }
    echo "Menü öğesi #{$table->id}: {$m['menutype']} / {$m['title']}\n";
}

(new Menu($db))->rebuild(1);

// Ana menüdeki News öğesini Latest kategorisine bağla
$newsParams = json_encode(['layout_type' => 'blog', 'num_leading_articles' => 0, 'num_intro_articles' => 9, 'num_columns' => 3, 'show_page_heading' => 1]);
$db->setQuery("UPDATE #__menu SET link = 'index.php?option=com_content&view=category&layout=blog&id={$latestCatId}', params = " . $db->quote($newsParams) . " WHERE id = 104")->execute();

// Ana sayfa: bileşen alanı boş kalsın (Latest artık modülden geliyor)
$homeParams = json_encode(['num_leading_articles' => 0, 'num_intro_articles' => 0, 'num_links' => 0, 'show_page_heading' => 0, 'show_no_articles' => 0]);
$db->setQuery('UPDATE #__menu SET params = ' . $db->quote($homeParams) . ' WHERE id = 101')->execute();
echo "Ana menü News -> Latest; ana sayfa bileşen alanı boşaltıldı\n";

// ---------- 5) Modüller ----------
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

// Eski footer custom modüllerini kaldır (113 Explore, 114 Get involved, 115 Contact eski hali)
$db->setQuery('DELETE FROM #__modules WHERE id IN (113,114,115)')->execute();
$db->setQuery('DELETE FROM #__modules_menu WHERE moduleid IN (113,114,115)')->execute();

// Modül adlarını netleştir
$db->setQuery("UPDATE #__modules SET title = 'Manşet (Hero)' WHERE id = 110")->execute();
$db->setQuery("UPDATE #__modules SET title = 'Contribute' WHERE id = 112")->execute();

// Get Active — mor kutu, menüden beslenir (yalnız ana sayfa)
wfp_add_module($db, 'Get Active', 'below-top', 'mod_menu', '', [
    'menutype' => 'getactive', 'base' => '', 'startLevel' => 1, 'endLevel' => 0,
    'showAllChildren' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900,
], 1, 101);

// Latest News — mod_articles + wfpcards yerleşimi (yalnız ana sayfa)
wfp_add_module($db, 'Latest News', 'main-top', 'mod_articles', '', [
    'mode' => 'normal', 'catid' => [(string) $latestCatId], 'count' => 3,
    'show_child_category_articles' => 0, 'levels' => 1, 'show_featured' => '',
    'article_ordering' => 'a.publish_up', 'article_ordering_direction' => 'DESC',
    'item_title' => 1, 'link_titles' => 1, 'item_heading' => 'h2',
    'show_date' => 1, 'show_date_field' => 'published', 'show_date_format' => 'd F Y',
    'show_introtext' => 1, 'introtext_limit' => 160,
    'show_readmore' => 1, 'show_category' => 0, 'show_author' => 0, 'show_hits' => 0,
    'layout' => 'wfp:wfpcards', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900,
], 1, 101);

// Feature (Siyah Bant) — özel HTML (yalnız ana sayfa)
$featureHtml = <<<HTML
<span class="wfp-feature-sub">We fight to win</span>
<h2>When we organize, we win</h2>
<p>From city councils to state houses, our members are proving that people power beats big money. Join a campaign near you and be part of the next victory.</p>
<div class="wfp-feature-links">
	<a class="wfp-btn" href="index.php?option=com_content&amp;view=article&amp;id=5&amp;Itemid=105">Take action</a>
	<a class="wfp-under-link" href="index.php?option=com_content&amp;view=category&amp;layout=blog&amp;id=2&amp;Itemid=103">See our fights</a>
</div>
HTML;
wfp_add_module($db, 'Feature (Siyah Bant)', 'main-bottom', 'mod_custom', $featureHtml, [
    'prepare_content' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900, 'style' => '0',
], 0, 101);

// Footer menüleri (tüm sayfalar)
wfp_add_module($db, 'Footer Menu', 'footer-a', 'mod_menu', '', [
    'menutype' => 'footermenu', 'base' => '', 'startLevel' => 1, 'endLevel' => 0,
    'showAllChildren' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900,
], 0, 0);

wfp_add_module($db, 'Footer Legal', 'footer-b', 'mod_menu', '', [
    'menutype' => 'footerlegal', 'base' => '', 'startLevel' => 1, 'endLevel' => 0,
    'showAllChildren' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900,
], 0, 0);

$contactHtml = <<<HTML
<h2>Contact us</h2>
<p>123 Movement St, Anytown<br>hello@example.org &middot; +1 (000) 000-0000</p>
HTML;
wfp_add_module($db, 'Footer Contact', 'footer-c', 'mod_custom', $contactHtml, [
    'prepare_content' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900, 'style' => '0',
], 0, 0);

$disclaimerHtml = <<<HTML
<p>We are a grassroots, multiracial movement of working people fighting for a nation that works for the many, not the few. Contributions are not tax deductible.</p>
<div class="wfp-paid-for">Paid for by Working Families. Not authorized by any candidate or candidate's committee.</div>
HTML;
wfp_add_module($db, 'Footer Disclaimer', 'copyright', 'mod_custom', $disclaimerHtml, [
    'prepare_content' => 0, 'layout' => '_:default', 'moduleclass_sfx' => '', 'cache' => 1, 'cache_time' => 900, 'style' => '0',
], 0, 0);

echo "Bitti.\n";
