<?php

/**
 * @package     Solidarity
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$wa = $this->getWebAssetManager();
$wa->registerAndUseStyle('template.solidarity.main', 'templates/' . $this->template . '/css/template.css', ['version' => '1.3.0']);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="site contentpane">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
</body>
</html>
