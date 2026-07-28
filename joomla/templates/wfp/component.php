<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$wa = $this->getWebAssetManager();
$wa->registerAndUseStyle('template.wfp', 'templates/' . $this->template . '/css/template.css', ['version' => $this->version]);
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="site component-body">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
