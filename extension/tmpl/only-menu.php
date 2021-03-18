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

$dynamicClasses = [
    'layout-' . $this->getLayoutId()
];
?>
<div class="mod-phproberto-bs5-navbar <?= trim(implode(' ', $dynamicClasses)) ?>" id="<?= $id ?>">
    <?= $moduleInstance->render('default.menu') ?>
</div>