<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\ErrorDocument $this */

$app      = Factory::getApplication();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->error->getCode(); ?> — <?php echo $sitename; ?></title>
	<link rel="stylesheet" href="<?php echo Uri::root(true); ?>/templates/<?php echo $this->template; ?>/css/template.css">
</head>
<body class="site">
	<div class="wfp-error-page">
		<div>
			<div class="wfp-error-code"><?php echo $this->error->getCode(); ?></div>
			<h1><?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></h1>
			<p><a class="wfp-btn wfp-btn-purple" href="<?php echo Route::_('index.php'); ?>"><?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
		</div>
	</div>
</body>
</html>
