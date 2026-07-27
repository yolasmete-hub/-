<?php

/**
 * @package     Solidarity
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app      = Factory::getApplication();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$cssPath  = $this->baseurl . '/templates/' . $this->template . '/css/template.css';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo $cssPath; ?>">
    <style>
        .sol-offline {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sol-purple-900);
            padding: 2rem 1.25rem;
        }
        .sol-offline__card {
            width: 100%;
            max-width: 26rem;
            background: var(--sol-cream);
            border: 0.25rem solid var(--sol-purple-950);
            border-radius: var(--sol-radius);
            box-shadow: 0.6rem 0.6rem 0 rgba(255, 92, 43, 0.85);
            padding: 2.5rem 2rem;
        }
        .sol-offline__card label {
            display: block;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin: 1rem 0 0.35rem;
        }
    </style>
</head>
<body class="site">
    <div class="sol-offline">
        <div class="sol-offline__card">
            <h1><?php echo $sitename; ?></h1>
            <?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) !== '') : ?>
                <p><?php echo $app->get('offline_message'); ?></p>
            <?php endif; ?>

            <jdoc:include type="message" />

            <form action="<?php echo Route::_('index.php', true); ?>" method="post">
                <label for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
                <input type="text" name="username" id="username" required>

                <label for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
                <input type="password" name="password" id="password" required>

                <p style="margin-top:1.5rem;">
                    <button type="submit" class="sol-btn sol-btn--accent"><?php echo Text::_('JLOGIN'); ?></button>
                </p>

                <input type="hidden" name="option" value="com_users">
                <input type="hidden" name="task" value="user.login">
                <input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>
</body>
</html>
