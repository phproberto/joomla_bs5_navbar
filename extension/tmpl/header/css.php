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

extract($displayData);

$wa = Factory::getDocument()->getWebAssetManager();

if ($params->get('load_bootstrap_css', 'none') === 'cdn') {
    $wa->registerAsset('style', 'twitter_bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css')
        ->useAsset('style', 'twitter_bootstrap5');
} elseif ($params->get('load_bootstrap_css', 'none') === 'local') {
    $wa->registerAsset('style', 'twitter_bootstrap5', 'mod_phproberto_bs5_navbar/bootstrap.min.css')
        ->useAsset('style', 'twitter_bootstrap5');
}

if ((int) $params->get('show_dropdowns_on_over', '1') === 1) {
    $css = <<<CSS
    #$id .navbar-collapse:not(.show) .dropdown:hover > .dropdown-menu {
        display: block;
    }
    #$id .navbar-collapse:not(.show) .dropdown.level-2 .dropdown-menu {
        margin-left: 150px;
        margin-top: -28px;
    }
    CSS;
    $wa->addInline('style', $css);
}
