<?php

/**
 * @package     Solidarity
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 *
 * Module chrome "card": white card with border, hard shadow and orange
 * underlined title. Select with style="card" or as a module chrome.
 */

defined('_JEXEC') or die;

$module = $displayData['module'];
$params = $displayData['params'];

if ((string) $module->content === '') {
    return;
}

$moduleTag      = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
$headerTag      = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
$headerClass    = htmlspecialchars($params->get('header_class', ''), ENT_QUOTES, 'UTF-8');
$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');

?>
<<?php echo $moduleTag; ?> class="sol-card <?php echo $moduleClassSfx; ?>">
    <?php if ((bool) $module->showtitle) : ?>
        <<?php echo $headerTag; ?> class="sol-card__title <?php echo $headerClass; ?>"><?php echo $module->title; ?></<?php echo $headerTag; ?>>
    <?php endif; ?>
    <?php echo $module->content; ?>
</<?php echo $moduleTag; ?>>
