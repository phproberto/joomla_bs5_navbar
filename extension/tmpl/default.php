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

if ($params->get('load_bootstrap_css', 'cdn') === 'cdn') {
    $wa->registerAsset('style', 'twitter_bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css')
        ->useAsset('style', 'twitter_bootstrap5');
} elseif ($params->get('load_bootstrap_css', 'cdn') === 'local') {
    $wa->registerAsset('style', 'twitter_bootstrap5', 'mod_phproberto_bs5_navbar/bootstrap.min.css')
        ->useAsset('style', 'twitter_bootstrap5');
}

if ($params->get('load_bootstrap_js', 'cdn') === 'cdn') {
    $wa->registerAsset('script', 'twitter_bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js', [], ['defer' => true])
        ->useAsset('script', 'twitter_bootstrap5');
} elseif ($params->get('load_bootstrap_js', 'cdn') === 'local') {
    $wa->registerAsset('script', 'mod_phproberto_bs5_navbar', 'mod_phproberto_bs5_navbar/bootstrap.bundle.min.js', [], ['defer' => true])
        ->useAsset('script', 'mod_phproberto_bs5_navbar');
}

$id = '';

if (($tagId = $params->get('tag_id', ''))) {
    $id = ' id="' . $tagId . '"';
}

$moduleClass = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

$customBackgroundColor = $params->get('bg_color_custom', '#4c026e');
$useCustomBackgroundColor = $params->get('bg_color', 'bg-secondary') === 'custom';
?>
<nav class="navbar navbar-expand-lg <?= $params->get('color_scheme', 'navbar-dark') ?> <?= $params->get('bg_color', 'bg-secondary') ?> <?php echo $moduleClass; ?> " <?php if ($useCustomBackgroundColor) : ?> style="background-color: <?= $customBackgroundColor ?>" <?php endif; ?>>
    <div class="container">
        <?= $moduleInstance->render('default.modules', array_merge($displayData, ['position' => $module->position . '-navbar-start'])) ?>
        <?php if ($params->get('show_brand', '1') === '1') : ?>
            <?= $this->sublayout('brand', $displayData) ?>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar" class="navbar-collapse collapse">
            <?= $moduleInstance->render('menu', $displayData) ?>
            <?php if ($params->get('show_search', '1') === '1') : ?>
                <?= $moduleInstance->render('default.searchbox', $displayData) ?>
            <?php endif; ?>
            <?= $moduleInstance->render('default.modules', array_merge($displayData, ['position' => $module->position . '-navbar-end'])) ?>
        </div>
    </div>
</nav>