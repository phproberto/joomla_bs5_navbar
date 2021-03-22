<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

/** @var Phproberto\Joomla\Module\Bootstrap5\Navbar\Module $moduleInstance */

/** @var Joomla\CMS\Document\Document $doc */
$doc = Factory::getDocument();

if ($moduleInstance->isJoomla4()) {
    $wa = $doc->getWebAssetManager();

    if ($params->get('load_bootstrap_js', 'none') === 'cdn') {
        $wa->registerAsset('script', 'twitter_bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js', [], ['defer' => true])
            ->useAsset('script', 'twitter_bootstrap5');
    } elseif ($params->get('load_bootstrap_js', 'none') === 'local') {
        $wa->registerAsset('script', 'mod_phproberto_bs5_navbar', 'mod_phproberto_bs5_navbar/bootstrap.bundle.min.js', [], ['defer' => true])
            ->useAsset('script', 'mod_phproberto_bs5_navbar');
    }
    return;
}

if ($params->get('load_bootstrap_js', 'none') === 'cdn') {
    $doc->addScript('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js', [], ['defer' => true]);
} elseif ($params->get('load_bootstrap_js', 'none') === 'local') {
    HTMLHelper::stylesheet('mod_phproberto_bs5_navbar/bootstrap.bundle.min.js', false, true, false);
}
