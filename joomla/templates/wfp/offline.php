<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$app      = Factory::getApplication();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo Uri::root(true); ?>/templates/<?php echo $this->template; ?>/css/template.css">
</head>
<body class="site">
	<div class="wfp-error-page">
		<div>
			<h1><?php echo $sitename; ?></h1>
			<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) !== '') : ?>
				<p><?php echo $app->get('offline_message'); ?></p>
			<?php endif; ?>
			<jdoc:include type="message" />
			<form action="<?php echo Uri::root(true); ?>/index.php" method="post" id="form-login">
				<p><input name="username" id="username" type="text" placeholder="<?php echo Text::_('JGLOBAL_USERNAME'); ?>"></p>
				<p><input name="password" id="password" type="password" placeholder="<?php echo Text::_('JGLOBAL_PASSWORD'); ?>"></p>
				<p><button type="submit" class="wfp-btn wfp-btn-purple"><?php echo Text::_('JLOGIN'); ?></button></p>
				<input type="hidden" name="option" value="com_users">
				<input type="hidden" name="task" value="user.login">
				<input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>
		</div>
	</div>
</body>
</html>
