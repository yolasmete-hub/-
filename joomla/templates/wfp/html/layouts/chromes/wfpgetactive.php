<?php

/**
 * @package     wfp
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 *
 * "Get Active" kutusu kromu: mor kutu, sarı alt çizgili başlık.
 * İçine bir Menü modülü (Menus » Get Active) atanır.
 */

defined('_JEXEC') or die;

$module = $displayData['module'];

if (!$module->content) {
    return;
}
?>
<div class="wfp-getactive">
	<?php if ($module->showtitle) : ?>
		<h2 class="wfp-getactive-title"><?php echo $module->title; ?></h2>
	<?php endif; ?>
	<?php echo $module->content; ?>
</div>
