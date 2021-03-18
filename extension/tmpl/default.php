<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$moduleInstance->render('header.css');
$moduleInstance->render('header.js');

$customBackgroundColor = $params->get('bg_color_custom', '#4c026e');
$useCustomBackgroundColor = $params->get('bg_color', 'bg-secondary') === 'custom';

$dynamicClasses = [
    $params->get('color_scheme', 'navbar-dark'),
    'layout-' . $this->getLayoutId(),
    $params->get('bg_color', 'bg-secondary'),
    $moduleClass
];
?>
<nav class="navbar navbar-expand-lg mod-phproberto-bs5-navbar <?= trim(implode(' ', $dynamicClasses)) ?>" <?php if ($useCustomBackgroundColor) : ?> style="background-color: <?= $customBackgroundColor ?>" <?php endif; ?> id="<?= $id ?>">
    <div class="container">
        <?= $moduleInstance->render('default.modules', ['position' => $params->get('modules_beginning_position', 'navbar-begin')]) ?>
        <?php if ($params->get('show_brand', '1') === '1') : ?>
            <?= $moduleInstance->render('default.brand') ?>
        <?php endif; ?>
        <?= $moduleInstance->render('default.modules', ['position' => $params->get('modules_after_brand_position', 'navbar-after-brand')]) ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar" class="navbar-collapse collapse">
            <?= $moduleInstance->render('default.menu') ?>
            <?php if ($params->get('show_search', '1') === '1') : ?>
                <?= $moduleInstance->render('default.searchbox') ?>
            <?php endif; ?>
            <?= $moduleInstance->render('default.modules', ['position' => $params->get('modules_end_position', 'navbar-end')]) ?>
        </div>
    </div>
</nav>