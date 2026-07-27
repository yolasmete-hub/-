<?php

/**
 * @package     Solidarity
 * @copyright   (C) 2026 Mete Yolaş
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\Document\ErrorDocument $this */

$app      = Factory::getApplication();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$code     = (int) $this->error->getCode();
$message  = htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');
$cssPath  = $this->baseurl . '/templates/' . $this->template . '/css/template.css';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $code; ?> | <?php echo $sitename; ?></title>
    <link rel="stylesheet" href="<?php echo $cssPath; ?>">
    <style>
        .sol-error {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sol-purple-900);
            padding: 2rem 1.25rem;
        }
        .sol-error__card {
            max-width: 34rem;
            text-align: center;
            background: var(--sol-cream);
            border: 0.25rem solid var(--sol-purple-950);
            border-radius: var(--sol-radius);
            box-shadow: 0.6rem 0.6rem 0 rgba(255, 92, 43, 0.85);
            padding: 3rem 2rem;
        }
        .sol-error__code {
            font-family: var(--sol-font-display);
            font-size: clamp(4rem, 12vw, 7rem);
            line-height: 1;
            color: var(--sol-orange);
            display: block;
        }
    </style>
</head>
<body class="site">
    <div class="sol-error">
        <div class="sol-error__card">
            <span class="sol-error__code"><?php echo $code; ?></span>
            <h1><?php echo Text::_('TPL_SOLIDARITY_ERROR_TITLE'); ?></h1>
            <p><?php echo $message; ?></p>
            <a class="sol-btn sol-btn--accent" href="<?php echo $this->baseurl; ?>/index.php"><?php echo Text::_('TPL_SOLIDARITY_ERROR_HOME'); ?></a>
        </div>
    </div>
</body>
</html>
