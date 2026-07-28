<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$module  = $displayData['module'];
$params  = $displayData['params'];

if (!$module->content) {
    return;
}

$moduleTag     = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
$headerTag     = htmlspecialchars($params->get('header_tag', 'h2'), ENT_QUOTES, 'UTF-8');
$moduleClass   = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
$headerClass   = htmlspecialchars($params->get('header_class', ''), ENT_QUOTES, 'UTF-8');
?>
<<?php echo $moduleTag; ?> class="wfp-module <?php echo $moduleClass; ?>">
	<?php if ($module->showtitle) : ?>
		<<?php echo $headerTag; ?> class="wfp-module-title <?php echo $headerClass; ?>"><span><?php echo $module->title; ?></span></<?php echo $headerTag; ?>>
	<?php endif; ?>
	<?php echo $module->content; ?>
</<?php echo $moduleTag; ?>>
