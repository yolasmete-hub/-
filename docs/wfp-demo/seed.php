<?php
// Demo content seeder for the wfp template — run from the joomla root:
//   php seed.php
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
$app = $container->get(\Joomla\Console\Application::class);
Factory::$application = $app;
$db = Factory::getDbo();

$adminId = 272;
$catId   = 2;
$now     = '2026-07-28 12:00:00';

// ---------- Articles ----------
$articles = [
    [
        'title' => 'We Won Paid Sick Leave for 1.2 Million Workers',
        'intro' => '<p>After a two-year grassroots campaign led by working families across the state, the legislature passed one of the strongest paid sick leave laws in the country.</p>',
        'full'  => '<p>This victory belongs to the thousands of members who knocked doors, made calls and told their stories. When we organize, we win.</p>',
    ],
    [
        'title' => 'Housing Is a Human Right: Our 2026 Platform',
        'intro' => '<p>Rents are up, wages are flat, and corporate landlords are cashing in. Our new housing platform puts tenants and first-time homebuyers first.</p>',
        'full'  => '<p>We are fighting for universal rent stabilization, a massive investment in social housing, and an end to speculative evictions.</p>',
    ],
    [
        'title' => 'Meet the Candidates Running on Our Line This Fall',
        'intro' => '<p>From school boards to the state house, a new generation of working-class champions is stepping up — nurses, teachers, drivers and organizers.</p>',
        'full'  => '<p>Every one of them signed our pledge: no corporate PAC money, and a platform written by and for working people.</p>',
    ],
    [
        'title' => 'About Us',
        'intro' => '<p>We are a grassroots, multiracial movement of working people. We recruit, train and elect leaders who come from our communities and answer to them — not to big donors.</p>',
        'full'  => '<p>Founded by unions and community organizations, we believe the people closest to the problem are closest to the solution. Join us and build power where you live.</p>',
        'featured' => false,
    ],
    [
        'title' => 'Take Action',
        'intro' => '<p>Real change starts with people like you. Volunteer for a campaign, join a local chapter, or chip in to power the movement.</p>',
        'full'  => '<ul><li>Knock doors with a local team</li><li>Make calls from home</li><li>Host a house meeting</li><li>Become a monthly donor</li></ul>',
        'featured' => false,
    ],
];

$articleIds = [];
$ordering = 1;

foreach ($articles as $i => $a) {
    $table = new Content($db);
    $data = [
        'title'      => $a['title'],
        'alias'      => '',
        'introtext'  => $a['intro'],
        'fulltext'   => $a['full'],
        'state'      => 1,
        'catid'      => $catId,
        'created'    => $now,
        'created_by' => $adminId,
        'publish_up' => $now,
        'attribs'    => '{}',
        'metadata'   => '{"robots":"","author":"","rights":""}',
        'images'     => '{}',
        'urls'       => '{}',
        'language'   => '*',
        'access'     => 1,
        'featured'   => (int) ($a['featured'] ?? true),
        'metadesc'   => '',
        'metakey'    => '',
    ];
    if (!$table->bind($data) || !$table->check() || !$table->store()) {
        echo 'Article failed: ' . $a['title'] . ' — ' . $table->getError() . "\n";
        continue;
    }
    $articleIds[$a['title']] = (int) $table->id;
    if ($a['featured'] ?? true) {
        $db->setQuery("INSERT INTO #__content_frontpage (content_id, ordering, featured_up, featured_down) VALUES ({$table->id}, {$ordering}, NULL, NULL)")->execute();
        $ordering++;
    }
    echo "Article #{$table->id}: {$a['title']}\n";
}

// ---------- Menu items ----------
$componentId = (int) $db->setQuery("SELECT extension_id FROM #__extensions WHERE element='com_content' AND type='component'")->loadResult();

$menuItems = [
    [
        'title' => 'About',
        'link'  => 'index.php?option=com_content&view=article&id=' . $articleIds['About Us'],
        'params'=> ['show_title' => 1],
    ],
    [
        'title' => 'Our Fights',
        'link'  => 'index.php?option=com_content&view=category&layout=blog&id=' . $catId,
        'params'=> ['layout_type' => 'blog', 'num_leading_articles' => 0, 'num_intro_articles' => 6, 'num_columns' => 3, 'show_page_heading' => 1],
    ],
    [
        'title' => 'News',
        'link'  => 'index.php?option=com_content&view=category&layout=blog&id=' . $catId,
        'params'=> ['layout_type' => 'blog', 'num_leading_articles' => 0, 'num_intro_articles' => 9, 'num_columns' => 3, 'show_page_heading' => 1],
    ],
    [
        'title' => 'Take Action',
        'link'  => 'index.php?option=com_content&view=article&id=' . $articleIds['Take Action'],
        'params'=> ['show_title' => 1],
    ],
];

foreach ($menuItems as $m) {
    $table = new Menu($db);
    $data = [
        'menutype'     => 'mainmenu',
        'title'        => $m['title'],
        'alias'        => '',
        'path'         => '',
        'link'         => $m['link'],
        'type'         => 'component',
        'published'    => 1,
        'component_id' => $componentId,
        'access'       => 1,
        'language'     => '*',
        'client_id'    => 0,
        'params'       => json_encode($m['params']),
        'browserNav'   => 0,
        'template_style_id' => 0,
        'img'          => '',
    ];
    $table->setLocation(1, 'last-child');
    if (!$table->bind($data) || !$table->check() || !$table->store()) {
        echo 'Menu failed: ' . $m['title'] . ' — ' . $table->getError() . "\n";
        continue;
    }
    echo "Menu #{$table->id}: {$m['title']}\n";
}

// Rebuild menu paths
$menuTable = new Menu($db);
$menuTable->rebuild(1);

// ---------- Home menu item: featured layout tuned for news cards ----------
$homeParams = json_encode([
    'num_leading_articles' => 0,
    'num_intro_articles'   => 3,
    'num_columns'          => 3,
    'multi_column_order'   => 1,
    'show_pagination'      => 2,
    'page_heading'         => 'Latest news',
    'show_page_heading'    => 1,
    'show_category'        => 0,
    'show_author'          => 0,
    'show_publish_date'    => 1,
    'show_create_date'     => 0,
    'show_hits'            => 0,
    'show_readmore'        => 1,
    'show_readmore_title'  => 0,
]);
$db->setQuery('UPDATE #__menu SET params = ' . $db->quote($homeParams) . ' WHERE id = 101')->execute();

echo "Done.\n";
